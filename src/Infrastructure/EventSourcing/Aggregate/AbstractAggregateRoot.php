<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Aggregate;

use App\Infrastructure\EventSourcing\Event\DomainEventInterface;
use App\Infrastructure\EventSourcing\Event\DomainMessage;
use App\Infrastructure\EventSourcing\Event\DomainMessagesStream;

abstract class AbstractAggregateRoot implements AggregateRootInterface
{
    protected $recordedEvents = [];

    /**
     * @var int
     */
    protected $playHead = 0;

    public static function reconstituteFrom(AggregateHistory $aggregateHistory): self
    {
        $reflection = new \ReflectionClass(static::class);

        /** @var AbstractAggregateRoot $instance */
        $instance = $reflection->newInstanceWithoutConstructor();
        $instance->replay($aggregateHistory);

        return $instance;
    }

    private function replay(AggregateHistory $aggregateHistory): void
    {
        /** @var DomainMessage $domainMessage */
        foreach ($aggregateHistory as $domainMessage) {
            $this->playHead = $domainMessage->getPlayHead();
            /** @var DomainEventInterface $event */
            $event = $domainMessage->getPayload();
            $this->apply($event);
        }
    }

    private function apply(DomainEventInterface $event): void
    {
        $method = $this->getApplyMethod($event);

        if (null !== $method) {
            $this->$method($event);
        }
    }

    private function getApplyMethod(DomainEventInterface $event): ?string
    {
        $filter =
            \ReflectionMethod::IS_PUBLIC
            | \ReflectionMethod::IS_PROTECTED
            | \ReflectionMethod::IS_PRIVATE;

        $ref = new \ReflectionClass($this);
        $methods = $ref->getMethods($filter);

        foreach ($methods as $method) {
            if (1 === $method->getNumberOfParameters()
                && preg_match('/^apply.+$/', $method->getName())
                && null !== $method->getParameters()[0]->getClass()
                && $method->getParameters()[0]->getClass()->getName() === \get_class($event)
            ) {
                return $method->getName();
            }
        }

        return null;
    }

    /**
     * Get all the Domain Events that were recorded since the last time it was cleared, or since it was
     * restored from persistence. This does not include events that were recorded prior.
     */
    public function getRecordedEvents(): DomainMessagesStream
    {
        $events = new DomainMessagesStream($this->recordedEvents);
        $this->clearRecordedEvents();

        return $events;
    }

    /**
     * Clears the record of new Domain Events. This doesn't clear the history of the object.
     */
    public function clearRecordedEvents(): void
    {
        $this->recordedEvents = [];
    }

    public function getPlayHead(): int
    {
        return $this->playHead;
    }

    public function recordThat(DomainEventInterface $domainEvent): void
    {
        $this->handleRecursively($domainEvent);

        ++$this->playHead;

        $this->recordedEvents[] = DomainMessage::recordNow(
            $this->getAggregateId()->__toString(),
            $this->playHead,
            $domainEvent
        );
    }

    private function handleRecursively(DomainEventInterface $event): void
    {
        $this->apply($event);

        /** @var AbstractEntityChild $child */
        foreach ($this->getChildren() as $child) {
            $child->registerAggregateRoot($this);
            $child->handleRecursively($event);
        }
    }

    public function getChildren(): array
    {
        return [];
    }
}
