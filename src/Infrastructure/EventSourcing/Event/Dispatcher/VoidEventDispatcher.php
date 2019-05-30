<?php

namespace App\Infrastructure\EventSourcing\Event\Dispatcher;

use App\Infrastructure\EventSourcing\Event\DomainMessagesStream;

class VoidEventDispatcher implements DispatcherInterface
{
    public function dispatch(DomainMessagesStream $events): void
    {
        // In case of testing, we don't need to dispatch event's on the event bus
    }
}
