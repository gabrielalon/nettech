<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Aggregate;

use App\Infrastructure\EventSourcing\Aggregate\Exception\ReconstituteFailedException;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

interface AggregateRootInterface extends RecordsEventsInterface, TracksChangesInterface
{
    /**
     * @throws ReconstituteFailedException
     */
    public static function reconstituteFrom(AggregateHistory $aggregateHistory): AbstractAggregateRoot;

    public function recordThat(DomainEventInterface $domainEvent): void;

    public function getChildren(): array;
}
