<?php

namespace App\Tests\Infrastructure\CommandHandling;

use App\Infrastructure\EventSourcing\Aggregate\AggregateHistory;
use App\Infrastructure\EventSourcing\Aggregate\AggregateRootInterface;
use App\Infrastructure\EventSourcing\Aggregate\Persist\AggregateRepositoryInterface;
use App\Infrastructure\EventSourcing\Event\Persist\EventStorageRepositoryInterface;
use App\Infrastructure\EventSourcing\Event\Persist\InMemoryEventStorageRepository;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

/**
 * Helper testing scenario to test command handlers.
 *
 * The scenario will help with testing command handlers. A scenario consists of
 * three steps:
 *
 * 1) given(): Load a history of events in the event store
 * 2) when():  Dispatch a command
 * 3) then():  events that should have been persisted
 */
class Scenario
{
    private $commandHandler;
    private $testCase;
    private $aggregateId;
    private $aggregateClass;
    private $container;

    public function __construct(
        TestCase $testCase,
        MessageHandlerInterface $commandHandler,
        ContainerInterface $container
    ) {
        $this->testCase = $testCase;
        $this->commandHandler = $commandHandler;
        $this->container = $container;
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

    public function given(array $events = null): self
    {
        if (null === $events) {
            return $this;
        }

        /** @var AggregateRepositoryInterface $aggregateRepository */
        $aggregateRepository = $this->container->get(AggregateRepositoryInterface::class);

        /** @var AggregateRootInterface $aggregate */
        $aggregateRoot = $this->aggregateClass::reconstituteFrom(new AggregateHistory($this->aggregateId, $events));
        $aggregateRepository->save($aggregateRoot);

        return $this;
    }

    public function when($command): self
    {
        $this->commandHandler->__invoke($command);

        return $this;
    }

    public function then(array $events, int $playHead = 0): self
    {
        if (null === $this->aggregateId) {
            throw new \InvalidArgumentException('AggregateId must be set');
        }

        /** @var InMemoryEventStorageRepository $eventStore */
        $eventStore = $this->container->get(EventStorageRepositoryInterface::class);

        $this->testCase->assertEquals(
            $events,
            $eventStore->loadStream($this->aggregateId, $playHead)->toArray()
        );

        $eventStore->destroyStream();

        return $this;
    }
}
