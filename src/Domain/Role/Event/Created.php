<?php

namespace App\Domain\Role\Event;

use App\Domain\Role\Collection\PermissionCollection;
use App\Domain\Role\ValueObject\Name;
use App\Domain\Role\ValueObject\RoleId;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;

final class Created implements DomainEventInterface
{
    /**
     * @var RoleId
     */
    private $roleId;

    /**
     * @var PermissionCollection
     */
    private $permissions;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var \DateTimeImmutable
     */
    private $createdDate;

    public function __construct(
        RoleId $roleId,
        Name $name,
        PermissionCollection $permissions,
        \DateTimeImmutable $createdDate
    ) {
        $this->roleId = $roleId;
        $this->permissions = $permissions;
        $this->name = $name;
        $this->createdDate = $createdDate;
    }

    public function getPermissions(): PermissionCollection
    {
        return $this->permissions;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function getRoleId(): IdentifyInterface
    {
        return $this->roleId;
    }

    public function getCreatedDate(): \DateTimeImmutable
    {
        return $this->createdDate;
    }
}
