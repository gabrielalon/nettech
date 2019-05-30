<?php

namespace App\Tests\Infrastructure\EventSourcing\Aggregate\Factory;

use App\Infrastructure\EventSourcing\Aggregate\AbstractAggregateRoot;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;

interface FactoryInterface
{
    public function create(
        string $aggregateRootClass,
        IdentifyInterface $aggregateId,
        array $events
    ): AbstractAggregateRoot;
}
