<?php

namespace App\Infrastructure\EventSourcing\Snapshot\Persist;

use App\Infrastructure\EventSourcing\Aggregate\AggregateType;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;
use App\Infrastructure\EventSourcing\Snapshot\Persist\Exception\SnapshotNotFoundException;
use App\Infrastructure\EventSourcing\Snapshot\Snapshot;

final class InMemorySnapshotStorageRepository implements SnapshotStorageRepositoryInterface
{
    private $snapshots;

    public function __construct()
    {
        $this->snapshots = [];
    }

    public function save(Snapshot $snapshot): void
    {
        $this->snapshots[$snapshot->getAggregateId()] = $snapshot;
    }

    /**
     * @param string $aggregateId
     */
    public function get(AggregateType $aggregateType, IdentifyInterface $aggregateId): Snapshot
    {
        if (false === isset($this->snapshots[$aggregateId->__toString()])) {
            throw new SnapshotNotFoundException(
                sprintf(
                'Snapshot not found for aggregateId: %s',
                $aggregateId->__toString()
                )
            );
        }

        return $this->snapshots[$aggregateId->__toString()];
    }
}
