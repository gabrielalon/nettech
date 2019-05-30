<?php

namespace App\Infrastructure\Messenger\Transport\Amqp;

class AmqpFactory
{
    public function createConnection(array $credentials): \AMQPConnection
    {
        return new \AMQPConnection($credentials);
    }

    public function createChannel(\AMQPConnection $connection): \AMQPChannel
    {
        return new \AMQPChannel($connection);
    }

    public function createQueue(\AMQPChannel $channel): \AMQPQueue
    {
        return new \AMQPQueue($channel);
    }

    public function createExchange(\AMQPChannel $channel): \AMQPExchange
    {
        return new \AMQPExchange($channel);
    }
}
