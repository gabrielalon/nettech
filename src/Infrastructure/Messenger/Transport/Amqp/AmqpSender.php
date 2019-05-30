<?php

namespace App\Infrastructure\Messenger\Transport\Amqp;

use App\Infrastructure\Messenger\Stamp\CorrelationStamp;
use App\Infrastructure\Messenger\Stamp\HandleStamp;
use App\Infrastructure\Messenger\Stamp\ReplyToStamp;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class AmqpSender implements SenderInterface
{
    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(Connection $connection, SerializerInterface $serializer, LoggerInterface $logger)
    {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    public function send(Envelope $envelope): Envelope
    {
        try {
            $encodedMessage = $this->serializer->encode($envelope);

            $options = [
                'headers' => $encodedMessage['headers'],
            ];
            $flags = AMQP_NOPARAM;
            $routingKey = null;

            /** @var ReplyToStamp $replyToStamp */
            $replyToStamp = $envelope->last(ReplyToStamp::class);
            if (null !== $replyToStamp) {
                $flags = AMQP_AUTOACK;
                $routingKey = $replyToStamp->getId();
            }

            /** @var CorrelationStamp $correlationStamp */
            $correlationStamp = $envelope->last(CorrelationStamp::class);
            if (null !== $correlationStamp) {
                $options['correlation_id'] = $correlationStamp->getId();
            }

            unset($options['headers']['X-Message-Stamp-App\Infrastructure\Messenger\Stamp\CorrelationStamp']);

            $envelope = $this->handle($envelope, $encodedMessage['body'], $routingKey, $flags, $options);
        } catch (\Throwable $e) {
            $this->logger->error('Not handled exception occurred while message sending', [
                'message' => $e->getMessage(),
                'code'    => $e->getCode(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }

        return $envelope;
    }

    private function handle(Envelope $envelope, string $body, ?string $routingKey, int $flags, array $options): Envelope
    {
        $handleStamp = $envelope->last(HandleStamp::class);
        $correlationId = Uuid::uuid4();

        if (null !== $handleStamp) {
            $options['reply_to'] = 'amq.rabbitmq.reply-to';
            $options['correlation_id'] = $correlationId;
        }

        $this->connection->setup();
        $this->connection->publish($body, $routingKey, $flags, $options);

        if (null !== $handleStamp) {
            $start = microtime(true);

            do {
                $amqpEnvelope = $this->connection->get();

                if (null !== $amqpEnvelope && $amqpEnvelope->getReplyTo() === $correlationId) {
                    $resultEnvelope = $this->serializer->decode([
                        'body'    => $amqpEnvelope->getBody(),
                        'headers' => $amqpEnvelope->getHeaders(),
                    ]);

                    return $envelope->with(
                        new HandledStamp(
                            $resultEnvelope->getMessage(),
                            'ReplyTo'
                        )
                    );
                }
            } while (microtime(true) - $start <= 5);
        }

        return $envelope;
    }
}
