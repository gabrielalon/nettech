<?php

namespace App\Tests\Application\User\Command;

use App\Application\User\Command;
use App\Application\User\Event;
use App\Application\User\Service;
use App\Domain\Model\User\User;
use App\Domain\Model\User\Projection;
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
class CreateUserHandlerTest extends HandlerTestCase
{
    /** @var Service\UserCommandManager */
    private $command;

    public function setUp(): void
    {
        $repository = new UserRepository($this->getEventStorage(), $this->getSnapshotStorage());

        $this->register(Projection\UserProjector::class, new InMemoryUserProjector());
        $this->register(Command\CreateUserHandler::class, new Command\CreateUserHandler($repository));

        $this->command = new Service\UserCommandManager($this->getCommandBus());
    }

    /**
     * @test
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function itCreatesNewUserTest(): void
    {
        //given
        $uuid = VO\Identity\Uuid::fromIdentity(\Ramsey\Uuid\Uuid::uuid4()->toString());
        $login = VO\Char\Text::fromString('admin');
        $password = VO\Char\Text::fromString('admin');

        //when
        $this->command->create($uuid->toString(), $login->toString(), $password->toString());

        //then
        /** @var InMemoryUserProjector $projector */
        $projector = $this->container->get(Projection\UserProjector::class);
        $entity = $projector->get($uuid->toString());

        $this->assertEquals($entity->identifier(), $uuid->toString());

        $collection = $this->getStreamRepository()->load($uuid, 1);

        foreach ($collection->getArrayCopy() as $eventStream) {
            $event = $eventStream->getEventName();
            /** @var AggregateChanged $event */

            /** @var Event\NewUserCreated $event */
            $event = $event::fromEventStream($eventStream);

            $this->assertEquals($entity->identifier(), $event->userUuid()->toString());
            $this->assertEquals($entity->getLogin(), $event->userLogin()->toString());
            $this->assertEquals($entity->getPassword(), $event->userPassword()->toString());
        }

        $snapshot = $this->getSnapshotRepository()->get(AggregateType::fromAggregateRootClass(User::class), $uuid);
        $this->assertEquals($snapshot->getLastVersion(), 1);
    }
}
