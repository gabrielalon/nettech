<?php

namespace App\Infrastructure\EventSourcing\Aggregate;

use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

abstract class AbstractEntityChild implements EntityChildInterface
{
    /**
     * @var AggregateRootInterface
     */
    private $aggregateRoot;

    public function handleRecursively(DomainEventInterface $event): void
    {
        $this->apply($event);

        /** @var AbstractEntityChild $child */
        foreach ($this->getChildren() as $child) {
            $child->registerAggregateRoot($this->aggregateRoot);
            $child->handleRecursively($event);
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

    public function getChildren(): array
    {
        return [];
    }

    public function registerAggregateRoot(AggregateRootInterface $aggregateRoot): void
    {
        $this->aggregateRoot = $aggregateRoot;
    }

    protected function recordThat(DomainEventInterface $event): void
    {
        $this->aggregateRoot->recordThat($event);
    }
}
