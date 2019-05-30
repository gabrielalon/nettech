<?php

namespace App\Tests\Infrastructure\EventSourcing\Aggregate;

use App\Infrastructure\EventSourcing\Aggregate\AbstractAggregateRoot;
use App\Infrastructure\EventSourcing\Event\DomainMessage;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;
use App\Tests\Infrastructure\EventSourcing\Aggregate\Factory\FactoryInterface;
use PHPUnit\Framework\TestCase;

class Scenario
{
    /** @var AbstractAggregateRoot */
    private $aggregateRootInstance;
    private $aggregateRootClass;
    private $aggregateId;
    private $testCase;
    private $factory;

    /**
     * @var \Throwable|null
     */
    private $thrownException;

    public function __construct(TestCase $testCase, FactoryInterface $factory, string $aggregateRootClass)
    {
        $this->testCase = $testCase;
        $this->aggregateRootClass = $aggregateRootClass;
        $this->factory = $factory;
    }

    public function withAggregateId(IdentifyInterface $aggregateId): self
    {
        $this->aggregateId = $aggregateId;

        return $this;
    }

    public function withAggregateClass(string $aggregateClass): self
    {
        $this->aggregateClass = $aggregateClass;

        return $this;
    }

    public function given(array $givens): self
    {
        if (null === $this->aggregateId) {
            throw new \InvalidArgumentException('AggregateId must be set');
        }

        $this->aggregateRootInstance = $this->factory->create(
            $this->aggregateRootClass,
            $this->aggregateId,
            $givens
        );

        return $this;
    }

    public function givenCallable(callable $given): self
    {
        $this->aggregateRootInstance = $given();
        $this->aggregateRootInstance->clearRecordedEvents();

        return $this;
    }

    public function when(callable $when): self
    {
        if (null === $this->aggregateRootInstance) {
            try {
                $this->aggregateRootInstance = $when();
            } catch (\Throwable $e) {
                $this->thrownException = $e;
            }
        } else {
            $this->testCase::assertInstanceOf($this->aggregateRootClass, $this->aggregateRootInstance);

            try {
                $when($this->aggregateRootInstance);
            } catch (\Throwable $e) {
                $this->thrownException = $e;
            }
        }

        return $this;
    }

    public function then(array $thens, float $delta = 0.0): self
    {
        if (null === $this->thrownException) {
            $this->testCase::assertEquals($thens, $this->getEvents(), '', $delta);
        }

        return $this;
    }

    public function thenCallable(callable $then): self
    {
        if (null === $this->aggregateRootInstance) {
            throw new \LogicException('Can not call assertion callback on non existing aggregate');
        }

        $then($this->aggregateRootInstance);

        return $this;
    }

    public function thenExceptionThrown(string $class): void
    {
        $this->testCase::assertNotNull($this->thrownException, sprintf(
            'Expected exception "%s" but non was thrown',
            $class
        ));

        $this->testCase::assertInstanceOf($class, $this->thrownException, sprintf(
            'Expected exception "%s" but "%s" thrown',
            $class,
            \get_class($this->thrownException)
        ));

        $this->thrownException = null;
    }

    public function getThrownException(): ?\Throwable
    {
        return $this->thrownException;
    }

    private function getEvents(): array
    {
        return array_map(function (DomainMessage $message) {
            return $message->getPayload();
        }, $this->aggregateRootInstance->getRecordedEvents()->toArray());
    }
}
