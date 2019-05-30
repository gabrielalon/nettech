<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Snapshot\Persist;

use App\Infrastructure\EventSourcing\Aggregate\AggregateType;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;
use App\Infrastructure\EventSourcing\Snapshot\Snapshot;

interface SnapshotStorageRepositoryInterface
{
    public function save(Snapshot $snapshot): void;

    /**
     * @param string $aggregateId
     */
    public function get(AggregateType $aggregateType, IdentifyInterface $aggregateId): Snapshot;
}
