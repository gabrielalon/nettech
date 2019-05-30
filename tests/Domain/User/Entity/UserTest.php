<?php

namespace App\Tests\Domain\User\Entity;

use App\Domain\Role\ValueObject\RoleId;
use App\Domain\User\Entity\User;
use App\Domain\User\Event\Created;
use App\Domain\User\Event\FirstNameChanged;
use App\Domain\User\Event\LastNameChanged;
use App\Domain\User\Event\LoginChanged;
use App\Domain\User\Event\PasswordHashChanged;
use App\Domain\User\Event\Removed;
use App\Domain\User\Event\RoleAssigned;
use App\Domain\User\Event\RoleRemoved;
use App\Domain\User\Exception\UserRemovedException;
use App\Domain\User\StateFlag;
use App\Domain\User\ValueObject\FirstName;
use App\Domain\User\ValueObject\LastName;
use App\Domain\User\ValueObject\Login;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\UserId;
use App\Tests\Infrastructure\EventSourcing\Aggregate\AggregateRootScenarioTestCase;
use Ramsey\Uuid\Uuid;

/**
 * @group time-sensitive
 */
class UserTest extends AggregateRootScenarioTestCase
{
    protected function getAggregateRootClass(): string
    {
        return User::class;
    }

    public function testCreate(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->when(function () use ($userId) {
                return new User(
                    $userId,
                    new FirstName('Romeczek'),
                    new LastName('Romanowski'),
                    new Login('roman69'),
                    new Password('lubieKrzaki')
                );
            })
            ->then([
                new Created(
                    $userId,
                    new FirstName('Romeczek'),
                    new LastName('Romanowski'),
                    new Login('roman69'),
                    new Password('lubieKrzaki')
                ),
            ]);
    }

    public function testChangeName(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Ktos'),
                    new LastName('Ktosiowy'),
                    new Login('ktos'),
                    new Password('123123123')
                );

                return $user;
            })
            ->when(function (User $user) {
                $user->changeFirstName(new FirstName('Ktospozmianie'));
            })
            ->then([
                new FirstNameChanged(
                    $userId,
                    new FirstName('Ktospozmianie')
                ),
            ])
            ->thenCallable(function (User $user) {
                $this->assertSame(new FirstName('Ktospozmianie'), $user->getFirstName());
            });
    }

    public function testChangeLastName(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Test'),
                    new LastName('Testowy'),
                    new Login('test'),
                    new Password('345%26%@$*')
                );

                return $user;
            })
            ->when(function (User $user) {
                $user->changeLastName(new LastName('Costampozmianach'));
            })
            ->then([
                new LastNameChanged(
                    $userId,
                    new LastName('Costampozmianach')
                ),
            ])
            ->thenCallable(function (User $user) {
                $this->assertSame(new LastName('Costampozmianach'), $user->getLastName());
            });
    }

    public function testChangeLogin(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('werwer'),
                    new LastName('eeeee'),
                    new Login('login'),
                    new Password('123asd1')
                );

                return $user;
            })
            ->when(function (User $user) {
                $user->changeLogin(new Login('loginpozmianie'));
            })
            ->then([
                new LoginChanged(
                    $userId,
                    new Login('loginpozmianie')
                ),
            ])
            ->thenCallable(function (User $user) {
                $this->assertSame(new Login('loginpozmianie'), $user->getLogin());
            });
    }

    public function testChangePassword(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('werwer'),
                    new LastName('eeeee'),
                    new Login('login'),
                    new Password('te#4234*%#&ok')
                );

                return $user;
            })
            ->when(function (User $user) {
                $user->changePassword(new Password('po)()(@2942@&#^$*#('));
            })
            ->then([
                new PasswordHashChanged(
                    $userId,
                    new Password('po)()(@2942@&#^$*#(')
                ),
            ])
            ->thenCallable(function (User $user) {
                $this->assertSame(new Password('po)()(@2942@&#^$*#('), $user->getPasswordHash());
            });
    }

    public function testRemove(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Krzesełko'),
                    new LastName('Matador'),
                    new Login('matadorek'),
                    new Password('matadorro1231')
                );

                return $user;
            })
            ->when(function (User $user) {
                $user->remove();
            })
            ->then([
                new Removed(
                    $userId
                ),
            ])
            ->thenCallable(function (User $user) {
                $this->assertSame(StateFlag::REMOVED, $user->isRemoved());
            });
    }

    public function testRemoveRemoved(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Krzesełko'),
                    new LastName('Matador'),
                    new Login('matadorek'),
                    new Password('matadorro1231')
                );

                $user->remove();

                return $user;
            })
            ->when(function (User $user) {
                $user->remove();
            })
            ->thenExceptionThrown(UserRemovedException::class);
    }

    public function testChangeNameRemoved(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Krzesełko'),
                    new LastName('Matador'),
                    new Login('matadorek'),
                    new Password('matadorro1231')
                );

                $user->remove();

                return $user;
            })
            ->when(function (User $user) {
                $user->changeFirstName(new FirstName('aaassddd'));
            })
            ->thenExceptionThrown(UserRemovedException::class);
    }

    public function testChangeLastNameRemoved(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Krzesełko'),
                    new LastName('Peffjjf'),
                    new Login('matadorek'),
                    new Password('matadorro1231')
                );

                $user->remove();

                return $user;
            })
            ->when(function (User $user) {
                $user->changeLastName(new LastName('poietryw'));
            })
            ->thenExceptionThrown(UserRemovedException::class);
    }

    public function testChangeLogineRemoved(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Krzesełko'),
                    new LastName('Peffjjf'),
                    new Login('matadorek'),
                    new Password('matadorro1231')
                );

                $user->remove();

                return $user;
            })
            ->when(function (User $user) {
                $user->changeLastName(new LastName('poietryw'));
            })
            ->thenExceptionThrown(UserRemovedException::class);
    }

    public function testChangePasswordRemoved(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Krzesełko'),
                    new LastName('Peffjjf'),
                    new Login('matadorek'),
                    new Password('poIWYT^&#*W')
                );

                $user->remove();

                return $user;
            })
            ->when(function (User $user) {
                $user->changePassword(new Password('^#ieifP*#*'));
            })
            ->thenExceptionThrown(UserRemovedException::class);
    }

    public function testAssaignRole(): void
    {
        $userId = new UserId(Uuid::uuid4());
        $roleId = new RoleId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Jakistam'),
                    new LastName('mojenazwisko'),
                    new Login('tatata'),
                    new Password('&#&#&837387eui')
                );

                return $user;
            })
            ->when(function (User $user) use ($roleId) {
                $user->assignRole($roleId);
            })
            ->then([
                new RoleAssigned(
                    $userId,
                    $roleId
                ),
            ]);
    }

    public function testAssaignRoleRemovedUser(): void
    {
        $userId = new UserId(Uuid::uuid4());
        $roleId = new RoleId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Jakistam'),
                    new LastName('mojenazwisko'),
                    new Login('tatata'),
                    new Password('&#&#&837387eui')
                );

                $user->remove();

                return $user;
            })
            ->when(function (User $user) use ($roleId) {
                $user->assignRole($roleId);
            })
            ->thenExceptionThrown(UserRemovedException::class);
    }

    public function testRemoveRole(): void
    {
        $userId = new UserId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId) {
                $user = new User(
                    $userId,
                    new FirstName('Jakistam'),
                    new LastName('mojenazwisko'),
                    new Login('tatata'),
                    new Password('&#&#&837387eui')
                );

                $user->assignRole(new RoleId(Uuid::uuid4()));

                return $user;
            })
            ->when(function (User $user) {
                $user->removeRole();
            })
            ->then([
                new RoleRemoved(
                    $userId
                ),
            ]);
    }

    public function testRemoveRoleRemovedUser(): void
    {
        $userId = new UserId(Uuid::uuid4());
        $roleId = new RoleId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($userId, $roleId) {
                $user = new User(
                    $userId,
                    new FirstName('Jakistam'),
                    new LastName('mojenazwisko'),
                    new Login('tatata'),
                    new Password('&#&#&837387eui')
                );

                $user->assignRole($roleId);

                $user->remove();

                return $user;
            })
            ->when(function (User $user) use ($roleId) {
                $user->removeRole();
            })
            ->thenExceptionThrown(UserRemovedException::class);
    }
}
