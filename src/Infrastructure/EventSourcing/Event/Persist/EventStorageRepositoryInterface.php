<?php

namespace App\Infrastructure\EventSourcing\Event\Persist;

use App\Infrastructure\EventSourcing\Event\DomainMessage;
use App\Infrastructure\EventSourcing\Event\DomainMessagesStream;

interface EventStorageRepositoryInterface
{
    public function save(DomainMessage $domainMessage): void;

    public function saveStream(DomainMessagesStream $events): void;

    public function loadStream(string $aggregateId, int $playHead): DomainMessagesStream;
}
