<?php

namespace App\Infrastructure\EventSourcing\Event\Persist;

use App\Infrastructure\EventSourcing\Event\DomainEventInterface;
use App\Infrastructure\EventSourcing\Event\DomainMessage;
use App\Infrastructure\EventSourcing\Event\DomainMessagesStream;
use App\Infrastructure\EventSourcing\Event\Persist\Exception\DuplicateEntryException;

final class InMemoryEventStorageRepository implements EventStorageRepositoryInterface
{
    private $events;

    public function __construct()
    {
        $this->events = [];
    }

    public function loadStream(string $aggregateId, int $playHead): DomainMessagesStream
    {
        if (false === isset($this->events[$aggregateId])) {
            return new DomainMessagesStream();
        }

        $eventsFromPlayHead = array_filter(
            $this->events[$aggregateId],
            function (DomainEventInterface $event) use ($playHead) {
                return $playHead <= $event->getPlayHead();
            }
        );

        return new DomainMessagesStream($eventsFromPlayHead);
    }

    /**
     * @throws DuplicateEntryException
     */
    public function saveStream(DomainMessagesStream $events): void
    {
        foreach ($events as $event) {
            $this->save($event);
        }
    }

    /**
     * @throws DuplicateEntryException
     */
    public function save(DomainMessage $domainMessage): void
    {
        $aggregateId = $domainMessage->getId();

        if (isset($this->events[$aggregateId][$domainMessage->getPlayHead()])) {
            throw new DuplicateEntryException(
                sprintf(
                    'Duplicated event entry detected: %s (%s)',
                    $aggregateId,
                    $domainMessage->getPlayHead()
                )
            );
        }

        $this->events[$aggregateId][$domainMessage->getPlayHead()] = $domainMessage;
    }

    public function destroyStream(): void
    {
        $this->events = [];
    }
}
