<?php

namespace App\Infrastructure\Messenger\Transport\Amqp;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Transport\TransportInterface;

class AmqpTransport implements TransportInterface
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
     * @var ReceiverInterface
     */
    private $receiver;

    /**
     * @var SenderInterface
     */
    private $sender;

    public function __construct(
        Connection $connection,
        SerializerInterface $serializer,
        ReceiverInterface $receiver,
        SenderInterface $sender
    ) {
        $this->connection = $connection;
        $this->serializer = $serializer;
        $this->receiver = $receiver;
        $this->sender = $sender;
    }

    public function receive(callable $handler): void
    {
        $this->receiver->receive($handler);
    }

    public function stop(): void
    {
        $this->receiver->stop();
    }

    public function send(Envelope $envelope): Envelope
    {
        return $this->sender->send($envelope);
    }
}
