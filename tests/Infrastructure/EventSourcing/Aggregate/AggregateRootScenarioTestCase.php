<?php

namespace App\Tests\Infrastructure\EventSourcing\Aggregate;

use App\Infrastructure\EventSourcing\Aggregate\AbstractAggregateRoot;
use App\Tests\Infrastructure\EventSourcing\Aggregate\Factory\FactoryInterface;
use App\Tests\Infrastructure\EventSourcing\Aggregate\Factory\ReflectionFactory;
use Symfony\Bridge\PhpUnit\ClockMock;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group time-sensitive
 */
abstract class AggregateRootScenarioTestCase extends WebTestCase
{
    /** @var Scenario */
    protected $scenario;

    protected function setUp(): void
    {
        $this->scenario = $this->createScenario();

        ClockMock::register(AbstractAggregateRoot::class);
    }

    protected function tearDown(): void
    {
        if (null !== $this->scenario->getThrownException()) {
            throw $this->scenario->getThrownException();
        }
    }

    protected function createScenario(): Scenario
    {
        $aggregateRootClass = $this->getAggregateRootClass();
        $factory = $this->getAggregateRootFactory();

        return new Scenario($this, $factory, $aggregateRootClass);
    }

    abstract protected function getAggregateRootClass(): string;

    protected function getAggregateRootFactory(): FactoryInterface
    {
        return new ReflectionFactory();
    }
}
