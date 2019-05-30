<?php

namespace App\Infrastructure\EventSourcing\Event\Dispatcher;

use App\Infrastructure\EventSourcing\Event\DomainMessage;
use App\Infrastructure\EventSourcing\Event\DomainMessagesStream;
use Symfony\Component\Messenger\MessageBusInterface;

class EventDispatcher implements DispatcherInterface
{
    private $domainEventBus;

    public function __construct(MessageBusInterface $domainEventBus)
    {
        $this->domainEventBus = $domainEventBus;
    }

    public function dispatch(DomainMessagesStream $domainMessagesStream): void
    {
        /** @var DomainMessage $message */
        foreach ($domainMessagesStream as $message) {
            $this->domainEventBus->dispatch($message->getPayload());
        }
    }
}
