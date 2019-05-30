<?php

namespace App\Infrastructure\Testing;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class DomainTestCase extends KernelTestCase
{
    /**
     * @var MessageBusInterface
     */
    protected $domainCommandBus;

    /**
     * @var MessageBusInterface
     */
    protected $domainEventBus;

    /**
     * @var MessageBusInterface
     */
    protected $domainQueryBus;

    protected function setUp(): void
    {
        static::bootKernel(['debug' => false]);

        $this->domainCommandBus = static::$container->get('messenger.bus.domain.commands');
        $this->domainEventBus = static::$container->get('messenger.bus.domain.events');
        $this->domainQueryBus = static::$container->get('messenger.bus.domain.queries');
    }

    protected function runAssertions(array $assertions, object $object, array $data): void
    {
        foreach ($data as $subject => $expectedValue) {
            $assertions[$subject]($expectedValue, $object);
        }
    }
}
