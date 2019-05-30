<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Aggregate;

class AggregateType
{
    /** @var string */
    protected $aggregateType;

    public static function fromAggregateRoot(AggregateRootInterface $aggregateRoot): self
    {
        return self::fromAggregateRootClass(\get_class($aggregateRoot));
    }

    public static function fromAggregateRootClass(string $aggregateRootClass): self
    {
        if (false === class_exists($aggregateRootClass)) {
            throw new \InvalidArgumentException(
                sprintf('Aggregate root class %s can not be found', $aggregateRootClass)
            );
        }

        $self = new static();
        $self->aggregateType = $aggregateRootClass;

        return $self;
    }

    public function __toString(): string
    {
        return $this->aggregateType;
    }
}
