<?php

namespace App\Infrastructure\EventSourcing\Snapshot\Factory;

use App\Infrastructure\EventSourcing\Aggregate\AggregateRootInterface;
use App\Infrastructure\EventSourcing\Aggregate\AggregateType;
use App\Infrastructure\EventSourcing\Snapshot\Snapshot;

class SnapshotFactory
{
    public static function createFromAggregate(AggregateRootInterface $aggregateRoot): Snapshot
    {
        return new Snapshot(
            AggregateType::fromAggregateRoot($aggregateRoot),
            $aggregateRoot->getAggregateId()->__toString(),
            $aggregateRoot,
            $aggregateRoot->getPlayHead(),
            new \DateTimeImmutable('@' . time())
        );
    }
}
