<?php

namespace App\Infrastructure\EventSourcing\Event\Dispatcher;

use App\Infrastructure\EventSourcing\Event\DomainMessagesStream;

interface DispatcherInterface
{
    public function dispatch(DomainMessagesStream $events): void;
}
