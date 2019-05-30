<?php

namespace App\Domain\Role\Event;

use App\Domain\Role\ValueObject\Permission;
use App\Domain\Role\ValueObject\RoleId;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

final class PermissionAssigned implements DomainEventInterface
{
    /**
     * @var RoleId
     */
    private $roleId;

    /**
     * @var Permission
     */
    private $permission;

    public function __construct(RoleId $roleId, Permission $permission)
    {
        $this->roleId = $roleId;
        $this->permission = $permission;
    }

    public function getPermission(): Permission
    {
        return $this->permission;
    }

    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }
}
