<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Aggregate\Persist;

use App\Infrastructure\EventSourcing\Aggregate\AggregateRootInterface;
use App\Infrastructure\EventSourcing\Aggregate\Exception\CorruptAggregateHistoryException;
use App\Infrastructure\EventSourcing\Aggregate\Exception\ReconstituteFailedException;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;

interface AggregateRepositoryInterface
{
    public function save(AggregateRootInterface $aggregateRoot): void;

    /**
     * @throws CorruptAggregateHistoryException
     * @throws ReconstituteFailedException
     */
    public function find(IdentifyInterface $uuid, string $aggregateClass): ?AggregateRootInterface;
}
