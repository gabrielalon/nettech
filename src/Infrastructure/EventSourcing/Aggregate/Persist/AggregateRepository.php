<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Aggregate\Persist;

use App\Infrastructure\EventSourcing\Aggregate\AggregateHistory;
use App\Infrastructure\EventSourcing\Aggregate\AggregateRootInterface;
use App\Infrastructure\EventSourcing\Aggregate\AggregateType;
use App\Infrastructure\EventSourcing\Aggregate\Exception\CorruptAggregateHistoryException;
use App\Infrastructure\EventSourcing\Aggregate\Exception\ReconstituteFailedException;
use App\Infrastructure\EventSourcing\Event\Dispatcher\DispatcherInterface;
use App\Infrastructure\EventSourcing\Event\Persist\EventStorageRepositoryInterface;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;
use App\Infrastructure\EventSourcing\Snapshot\Factory\SnapshotFactory;
use App\Infrastructure\EventSourcing\Snapshot\Persist\Exception\SnapshotNotFoundException;
use App\Infrastructure\EventSourcing\Snapshot\Persist\SnapshotStorageRepositoryInterface;

class AggregateRepository implements AggregateRepositoryInterface
{
    private $eventStorageRepository;
    private $snapshotStorage;
    private $eventDispatcher;

    public function __construct(
        EventStorageRepositoryInterface $eventStorageRepository,
        SnapshotStorageRepositoryInterface $snapshotStore,
        DispatcherInterface $eventDispatcher
    ) {
        $this->eventStorageRepository = $eventStorageRepository;
        $this->snapshotStorage = $snapshotStore;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function save(AggregateRootInterface $aggregateRoot): void
    {
        $recordedEvents = $aggregateRoot->getRecordedEvents();

        $this->eventStorageRepository->saveStream($recordedEvents);
        $this->eventDispatcher->dispatch($recordedEvents);
        $this->snapshotStorage->save(SnapshotFactory::createFromAggregate($aggregateRoot));
    }

    /**
     * @throws CorruptAggregateHistoryException
     * @throws ReconstituteFailedException
     *
     * @return AggregateRootInterface
     */
    public function find(IdentifyInterface $aggregateId, string $aggregateClass): ?AggregateRootInterface
    {
        try {
            $snapshot = $this->snapshotStorage->get(
                AggregateType::fromAggregateRootClass($aggregateClass),
                $aggregateId
            );
        } catch (SnapshotNotFoundException $exception) {
            return null;
        }

        $aggregateRoot = $snapshot->getAggregateRoot();

        // fixme: really +1?
        $events = $this->eventStorageRepository->loadStream(
            $aggregateId->__toString(),
            $aggregateRoot->getPlayHead() + 1
        );

        if ($events->count() > 0) {
            /** @var AggregateRootInterface $aggregateRoot */
            $aggregateRoot = $aggregateRoot::reconstituteFrom(
                new AggregateHistory($aggregateRoot->getAggregateId(), $events->toArray())
            );
        }

        return $aggregateRoot;
    }
}
