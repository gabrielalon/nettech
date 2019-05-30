<?php

namespace App\Infrastructure\Messenger\Transport\Amqp;

use App\Infrastructure\Messenger\Stamp\CorrelationStamp;
use App\Infrastructure\Messenger\Stamp\ReplyToStamp;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Transport\AmqpExt\Exception\RejectMessageExceptionInterface;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class AmqpReceiver implements ReceiverInterface
{
    /**
     * @var bool
     */
    private $shouldStop = false;

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

    /**
     * {@inheritdoc}
     */
    public function receive(callable $handler): void
    {
        $this->connection->setup();
        $this->connection->queue()->consume(
            function (\AMQPEnvelope $amqpEnvelope, \AMQPQueue $amqpQueue) use ($handler) {
                try {
                    $envelope = $this->serializer->decode([
                        'body'    => $amqpEnvelope->getBody(),
                        'headers' => $amqpEnvelope->getHeaders(),
                    ]);

                    if ('' !== $amqpEnvelope->getReplyTo()) {
                        $envelope = $envelope->with(new ReplyToStamp($amqpEnvelope->getReplyTo()));
                    }

                    if ('' !== $amqpEnvelope->getCorrelationId()) {
                        $envelope = $envelope->with(new CorrelationStamp($amqpEnvelope->getCorrelationId()));
                    }

                    $handler($envelope);

                    $this->connection->ack($amqpEnvelope);
                } catch (RejectMessageExceptionInterface $e) {
                    $this->logger->error('Not handled reject exception occurred while message receiving', [
                        'message' => $e->getMessage(),
                        'code'    => $e->getCode(),
                        'file'    => $e->getFile(),
                        'line'    => $e->getLine(),
                        'trace'   => $e->getTraceAsString(),
                    ]);

                    $this->connection->reject($amqpEnvelope);
                } catch (\Throwable $e) {
                    $this->logger->error('Not handled exception occurred while message receiving', [
                        'message' => $e->getMessage(),
                        'code'    => $e->getCode(),
                        'file'    => $e->getFile(),
                        'line'    => $e->getLine(),
                        'trace'   => $e->getTraceAsString(),
                    ]);

                    $this->connection->nack($amqpEnvelope);
                } finally {
                    if (\function_exists('pcntl_signal_dispatch')) {
                        pcntl_signal_dispatch();
                    }
                }

                if ($this->shouldStop) {
                    return false;
                }

                return null;
            }
        );
    }

    public function stop(): void
    {
        $this->shouldStop = true;
    }
}
