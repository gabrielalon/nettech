<?php

namespace App\Tests\Application;

use App\Tests\Mock\Container;
use N3ttech\Messaging\Command;
use N3ttech\Messaging\Event;
use N3ttech\Messaging\Query;
use N3ttech\Messaging\Snapshot;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;

abstract class HandlerTestCase extends PHPUnitTestCase
{
    /** @var Container */
    protected $container;

    /**
     * @param string $id
     * @param mixed  $value
     */
    protected function register(string $id, $value): void
    {
        if (null === $this->container) {
            $this->container = new Container();
        }

        if (false === $this->container->has($id)) {
            $this->container->register($id, $value);
        }
    }

    /**
     * @return Command\CommandBus
     */
    protected function getCommandBus(): Command\CommandBus
    {
        $commandHandlerProvider = new Command\CommandHandling\CommandHandlerProvider\Containerized();
        $commandHandlerProvider->setContainer($this->container);
        $commandRouterFactory = new Command\CommandRouting\CommandRouterFactory($commandHandlerProvider);
        $commandTransporterFactory = new Command\CommandTransporting\CommandTransporterFactory($commandRouterFactory->createDefault());

        return new Command\CommandBus($commandTransporterFactory->createDefault());
    }

    /**
     * @return Query\QueryBus
     */
    protected function getQueryBus(): Query\QueryBus
    {
        $queryHandlerProvider = new Query\QueryHandling\QueryHandlerProvider\Containerized();
        $queryHandlerProvider->setContainer($this->container);
        $queryRouterFactory = new Query\QueryRouting\QueryRouterFactory($queryHandlerProvider);
        $queryTransporterFactory = new Query\QueryTransporting\QueryTransporterFactory($queryRouterFactory->createDefault());

        return new Query\QueryBus($queryTransporterFactory->createDefault());
    }

    /**
     * @return Event\EventBus
     */
    protected function getEventBus(): Event\EventBus
    {
        /** @var string $dir */
        $dir = realpath(__DIR__.'/../events');
        $eventProjectionProvider = new Event\EventSourcing\EventProjectionProvider\Containerized();
        $eventProjectionProvider->setContainer($this->container);
        $eventRouterFactory = new Event\EventRouting\EventRouterFactory();
        $eventTransporterFactory = new Event\EventTransporting\EventTransporterFactory(
            $eventRouterFactory->fromDirectory($dir),
            $eventProjectionProvider
        );

        return new Event\EventBus($eventTransporterFactory->createDefault());
    }

    /**
     * @return Snapshot\Persist\InMemorySnapshotRepository
     */
    protected function getSnapshotRepository(): Snapshot\Persist\InMemorySnapshotRepository
    {
        $this->register(Snapshot\Persist\SnapshotRepository::class, new Snapshot\Persist\InMemorySnapshotRepository());

        return $this->container->get(Snapshot\Persist\SnapshotRepository::class);
    }

    /**
     * @return Snapshot\SnapshotStore\SnapshotStorage
     */
    protected function getSnapshotStorage(): Snapshot\SnapshotStore\SnapshotStorage
    {
        return new Snapshot\SnapshotStore\SnapshotStorage($this->getSnapshotRepository());
    }

    /**
     * @return Event\Persist\InMemoryEventStreamRepository
     */
    protected function getStreamRepository()
    {
        $this->register(Event\Persist\EventStreamRepository::class, new Event\Persist\InMemoryEventStreamRepository());

        return $this->container->get(Event\Persist\EventStreamRepository::class);
    }

    /**
     * @return Event\EventStore\EventStorage
     */
    protected function getEventStorage(): Event\EventStore\EventStorage
    {
        $eventStorageFactory = new Event\EventStore\EventStorageFactory($this->getStreamRepository());

        return $eventStorageFactory->create($this->getEventBus());
    }
}
