<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Snapshot;

use App\Infrastructure\EventSourcing\Aggregate\AggregateRootInterface;
use App\Infrastructure\EventSourcing\Aggregate\AggregateType;

final class Snapshot
{
    private $aggregateType;
    private $aggregateId;
    private $aggregateRoot;
    private $playHead;
    private $createdAt;

    public function __construct(
        AggregateType $aggregateType,
        string $aggregateId,
        AggregateRootInterface $aggregateRoot,
        int $playHead,
        \DateTimeImmutable $createdAt
    ) {
        $this->aggregateType = $aggregateType;
        $this->aggregateId = $aggregateId;
        $this->aggregateRoot = $aggregateRoot;
        $this->playHead = $playHead;
        $this->createdAt = $createdAt;
    }

    public function getAggregateType(): AggregateType
    {
        return $this->aggregateType;
    }

    public function getAggregateId(): string
    {
        return $this->aggregateId;
    }

    public function getAggregateRoot(): AggregateRootInterface
    {
        return $this->aggregateRoot;
    }

    public function getPlayHead(): int
    {
        return $this->playHead;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
