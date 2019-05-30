<?php

declare(strict_types=1);

namespace App\Domain\User\Entity;

use App\Domain\Role\ValueObject\RoleId;
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
use App\Infrastructure\EventSourcing\Aggregate\AbstractAggregateRoot;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;

final class User extends AbstractAggregateRoot
{
    /**
     * @var UserId
     */
    private $aggregateId;

    /**
     * @var FirstName
     */
    private $firstName;

    /**
     * @var LastName
     */
    private $lastName;

    /**
     * @var Login
     */
    private $login;

    /**
     * @var Password
     */
    private $passwordHash;

    /**
     * @var RoleId
     */
    private $roleId;

    /**
     * @var int
     */
    private $flags;

    public function __construct(
        UserId $aggregateId,
        FirstName $firstName,
        LastName $lastName,
        Login $login,
        Password $passwordHash
    ) {
        $this->recordThat(new Created($aggregateId, $firstName, $lastName, $login, $passwordHash));
    }

    public function changeFirstName(FirstName $firstName): void
    {
        $this->assertNotRemoved();

        if ($this->firstName !== $firstName) {
            $this->recordThat(new FirstNameChanged($this->aggregateId, $firstName));
        }
    }

    protected function assertNotRemoved(): void
    {
        if (StateFlag::REMOVED === ($this->flags & StateFlag::REMOVED)) {
            throw new UserRemovedException('Cannot operate on removed User');
        }
    }

    public function changeLastName(LastName $lastName): void
    {
        $this->assertNotRemoved();

        if ($this->lastName !== $lastName) {
            $this->recordThat(new LastNameChanged($this->aggregateId, $lastName));
        }
    }

    public function changeLogin(Login $login): void
    {
        $this->assertNotRemoved();

        if ($this->login !== $login) {
            $this->recordThat(new LoginChanged($this->aggregateId, $login));
        }
    }

    public function changePassword(Password $passwordHash): void
    {
        $this->assertNotRemoved();

        if ($this->passwordHash !== $passwordHash) {
            $this->recordThat(new PasswordHashChanged($this->aggregateId, $passwordHash));
        }
    }

    public function remove(): void
    {
        $this->assertNotRemoved();
        $this->recordThat(new Removed($this->aggregateId));
    }

    public function assignRole(RoleId $roleId): void
    {
        $this->assertNotRemoved();

        $this->recordThat(new RoleAssigned($this->aggregateId, $roleId));
    }

    public function removeRole(): void
    {
        $this->assertNotRemoved();

        $this->recordThat(new RoleRemoved($this->aggregateId));
    }

    public function getAggregateId(): IdentifyInterface
    {
        return $this->aggregateId;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getPasswordHash(): Password
    {
        return $this->passwordHash;
    }

    public function isRemoved(): bool
    {
        return StateFlag::REMOVED === ($this->flags & StateFlag::REMOVED);
    }

    protected function applyCreated(Created $event): void
    {
        $this->aggregateId = $event->getUserId();
        $this->firstName = $event->getFirstName();
        $this->lastName = $event->getLastName();
        $this->login = $event->getLogin();
        $this->passwordHash = $event->getPasswordHash();
        $this->roleId = null; // This means that user does not have any permissions yet
        $this->flags = 0;
    }

    protected function applyFirstNameChanged(FirstNameChanged $event): void
    {
        $this->firstName = $event->getFirstName();
    }

    protected function applyLastNameChanged(LastNameChanged $event): void
    {
        $this->lastName = $event->getLastName();
    }

    protected function applyLoginChanged(LoginChanged $event): void
    {
        $this->login = $event->getLogin();
    }

    protected function applyPasswordHashChanged(PasswordHashChanged $event): void
    {
        $this->passwordHash = $event->getPasswordHash();
    }

    protected function applyRemoved(Removed $event): void
    {
        $this->flags |= StateFlag::REMOVED;
    }

    protected function applyRoleAssigned(RoleAssigned $event): void
    {
        $this->roleId = $event->getRoleId();
    }

    protected function applyRoleRemoved(RoleRemoved $event): void
    {
        $this->roleId = null;
    }
}
