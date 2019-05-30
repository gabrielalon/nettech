<?php

namespace App\Infrastructure\EventSourcing\Aggregate;

use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;

interface EntityChildInterface
{
    public function registerAggregateRoot(AggregateRootInterface $aggregateRoot): void;

    public function getChildren(): array;

    public function getAggregateId(): IdentifyInterface;
}
