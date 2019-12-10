<?php

namespace App\Tests\Application\User;

use App\Application\User\Event;
use App\Domain\Model\User\User;
use App\Tests\Application\AggregateChangedTestCase;
use N3ttech\Messaging\Aggregate\AggregateRoot;
use N3ttech\Messaging\Aggregate\EventBridge\AggregateChanged;
use N3ttech\Valuing as VO;

/**
 * @internal
 * @coversNothing
 */
class UserTest extends AggregateChangedTestCase
{
    /** @var VO\Identity\Uuid */
    private $uuid;

    /** @var VO\Char\Text */
    private $login;

    /** @var VO\Char\Text */
    private $password;

    /**
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->uuid = VO\Identity\Uuid::fromIdentity(\Ramsey\Uuid\Uuid::uuid4()->toString());
        $this->login = VO\Char\Text::fromString('admin');
        $this->password = VO\Char\Text::fromString('admin');
    }

    /**
     * @test
     *
     * @throws \Assert\AssertionFailedException
     */
    public function itCreatesNewUserTest(): void
    {
        $user = User::createNewUser($this->uuid, $this->login, $this->password);

        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents($user);

        $this->assertCount(1, $events);

        /** @var Event\NewUserCreated $event */
        $event = $events[0];

        $this->assertSame(Event\NewUserCreated::class, $event->messageName());
        $this->assertTrue($this->uuid->equals($event->userUuid()));
        $this->assertTrue($this->login->equals($event->userLogin()));
        $this->assertTrue($this->password->equals($event->userPassword()));
    }

    /**
     * @test
     */
    public function itRemovesExistingUserTest(): void
    {
        /** @var User $user */
        $user = $this->reconstituteReturnPackageFromHistory($this->newUserCreated());
        $user->remove();

        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents($user);

        $this->assertCount(1, $events);

        /** @var Event\ExistingUserRemoved $event */
        $event = $events[0];

        $this->assertSame(Event\ExistingUserRemoved::class, $event->messageName());
    }

    /**
     * @param AggregateChanged ...$events
     *
     * @return AggregateRoot
     */
    private function reconstituteReturnPackageFromHistory(AggregateChanged ...$events): AggregateRoot
    {
        return $this->reconstituteAggregateFromHistory(
            User::class,
            $events
        );
    }

    /**
     * @return AggregateChanged
     */
    private function newUserCreated(): AggregateChanged
    {
        return Event\NewUserCreated::occur($this->uuid->toString(), [
            'creation_date' => time(),
            'login' => $this->login->toString(),
            'password' => $this->password->toString(),
        ]);
    }
}
