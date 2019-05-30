<?php

namespace App\Tests\Infrastructure\CommandHandling;

use App\Infrastructure\EventSourcing\Event\AbstractDomainEvent;
use Symfony\Bridge\PhpUnit\ClockMock;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

abstract class CommandHandlerScenarioTestCase extends WebTestCase
{
    /** @var Scenario */
    protected $scenario;

    public function setUp()
    {
        self::bootKernel();

        ClockMock::register(AbstractDomainEvent::class);

        $this->scenario = $this->createScenario();
    }

    protected function createScenario(): Scenario
    {
        $commandHandler = $this->createCommandHandler();

        return new Scenario($this, $commandHandler, self::$container);
    }

    /**
     * Create a command handler for the given scenario test case.
     */
    abstract protected function createCommandHandler(): MessageHandlerInterface;
}
