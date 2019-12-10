<?php

namespace App\Tests\Application\User\Command;

use App\Application\User\Command;
use App\Application\User\Event;
use App\Application\User\Service;
use App\Domain\Model\User\Projection;
use App\Domain\Model\User\User;
use App\Infrastructure\Persist\User\UserRepository;
use App\Tests\Application\HandlerTestCase;
use App\Tests\Infrastructure\Projection\User\InMemoryUserProjector;
use N3ttech\Messaging\Aggregate\AggregateType;
use N3ttech\Messaging\Aggregate\EventBridge\AggregateChanged;
use N3ttech\Valuing as VO;

/**
 * @internal
 * @coversNothing
 */
class RemoveUserHandlerTest extends HandlerTestCase
{
    /** @var Service\UserCommandManager */
    private $command;

    public function setUp(): void
    {
        $repository = new UserRepository($this->getEventStorage(), $this->getSnapshotStorage());

        $this->register(Projection\UserProjector::class, new InMemoryUserProjector());
        $this->register(Command\CreateUserHandler::class, new Command\CreateUserHandler($repository));
        $this->register(Command\RemoveUserHandler::class, new Command\RemoveUserHandler($repository));

        $this->command = new Service\UserCommandManager($this->getCommandBus());
    }

    /**
     * @test
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function itRemovesExistingUserTest(): void
    {
        //given
        $uuid = VO\Identity\Uuid::fromIdentity(\Ramsey\Uuid\Uuid::uuid4()->toString());
        $login = VO\Char\Text::fromString('admin');
        $password = VO\Char\Text::fromString('admin');
        $this->command->create($uuid->toString(), $login->toString(), $password->toString());

        //when
        $this->command->remove($uuid->toString());

        //then
        $collection = $this->getStreamRepository()->load($uuid, 2);

        foreach ($collection->getArrayCopy() as $eventStream) {
            $event = $eventStream->getEventName();
            /** @var AggregateChanged $event */

            /** @var Event\ExistingUserRemoved $event */
            $event = $event::fromEventStream($eventStream);

            $this->assertInstanceOf(Event\ExistingUserRemoved::class, $event);
        }

        $snapshot = $this->getSnapshotRepository()->get(AggregateType::fromAggregateRootClass(User::class), $uuid);
        $this->assertEquals($snapshot->getLastVersion(), 2);
    }
}
