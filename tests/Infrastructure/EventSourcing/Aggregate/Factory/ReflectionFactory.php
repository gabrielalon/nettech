<?php

namespace App\Tests\Infrastructure\EventSourcing\Aggregate\Factory;

use App\Infrastructure\EventSourcing\Aggregate\AbstractAggregateRoot;
use App\Infrastructure\EventSourcing\Aggregate\AggregateHistory;
use App\Infrastructure\EventSourcing\Event\DomainMessage;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;

class ReflectionFactory implements FactoryInterface
{
    public function create(
        string $aggregateRootClass,
        IdentifyInterface $aggregateId,
        array $events
    ): AbstractAggregateRoot {
        $reflection = new \ReflectionClass($aggregateRootClass);

        /** @var AbstractAggregateRoot $instance */
        $instance = $reflection->newInstanceWithoutConstructor();

        $domainMessages = [];
        $playHead = 0;

        foreach ($events as $event) {
            $domainMessages[] = DomainMessage::recordNow(
                $aggregateId->__toString(),
                ++$playHead,
                $event
            );
        }

        return $instance::reconstituteFrom(new AggregateHistory($aggregateId, $domainMessages));
    }
}
