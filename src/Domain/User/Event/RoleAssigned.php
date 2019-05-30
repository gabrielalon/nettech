<?php

namespace App\Domain\User\Event;

use App\Domain\Role\ValueObject\RoleId;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

final class RoleAssigned implements DomainEventInterface
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var RoleId
     */
    private $roleId;

    public function __construct(UserId $userId, RoleId $roleId)
    {
        $this->userId = $userId;
        $this->roleId = $roleId;
    }

    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
