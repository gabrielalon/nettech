<?php

namespace App\Domain\Role\Entity;

use App\Domain\Role\Collection\PermissionCollection;
use App\Domain\Role\Event\Created;
use App\Domain\Role\Event\PermissionAssigned;
use App\Domain\Role\Event\PermissionRevoked;
use App\Domain\Role\Event\Removed;
use App\Domain\Role\Exception\PermissionNotFoundException;
use App\Domain\Role\Exception\RoleRemovedException;
use App\Domain\Role\StateFlag;
use App\Domain\Role\ValueObject\Name;
use App\Domain\Role\ValueObject\Permission;
use App\Domain\Role\ValueObject\RoleId;
use App\Infrastructure\EventSourcing\Aggregate\AbstractAggregateRoot;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;

final class Role extends AbstractAggregateRoot
{
    /**
     * @var RoleId
     */
    private $aggregateId;

    /**
     * @var Name
     */
    private $name;

    /**
     * @var PermissionCollection
     */
    private $permissions;

    /**
     * @var int
     */
    private $flags;

    public function __construct(RoleId $aggregateId, Name $name, PermissionCollection $permissions)
    {
        $this->recordThat(new Created(
            $aggregateId,
            $name,
            $permissions,
            new \DateTimeImmutable('@' . time())
        ));
    }

    public function assignPermission(Permission $permission): void
    {
        $this->assertNotRemoved();
        $this->recordThat(new PermissionAssigned($this->aggregateId, $permission));
    }

    private function assertNotRemoved(): void
    {
        if (StateFlag::REMOVED === ($this->flags & StateFlag::REMOVED)) {
            throw new RoleRemovedException('Cannot operate on removed Role');
        }
    }

    public function revokePermission(Permission $permission): void
    {
        $this->assertNotRemoved();
        $this->assertPermissionExists($permission);
        $this->recordThat(new PermissionRevoked($this->aggregateId, $permission));
    }

    private function assertPermissionExists(Permission $permission): void
    {
        // @var Permission $permission
        foreach ($this->permissions as $internalPermission) {
            if ($internalPermission->equals($permission)) {
                return;
            }
        }

        throw new PermissionNotFoundException('Permission not found');
    }

    public function remove(): void
    {
        $this->assertNotRemoved();
        $this->recordThat(new Removed($this->aggregateId));
    }

    public function getAggregateId(): IdentifyInterface
    {
        return $this->aggregateId;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    public function hasPermission(string $subject, string $action): bool
    {
        /** @var Permission $permission */
        foreach ($this->permissions as $permission) {
            if ($permission->getSubject() === $subject && $permission->getAction() === $action) {
                return true;
            }

            return false;
        }
    }

    protected function applyCreated(Created $event): void
    {
        $this->aggregateId = $event->getRoleId();
        $this->name = $event->getName();
        $this->permissions = $event->getPermissions();
        $this->flags = 0;
    }

    protected function applyPermissionAssigned(PermissionAssigned $event): void
    {
        $this->permissions->assign($event->getPermission());
    }

    protected function applyPermissionRevoked(PermissionRevoked $event): void
    {
        $this->permissions->revoke($event->getPermission());
    }

    protected function applyRemoved(Removed $event): void
    {
        $this->flags |= StateFlag::REMOVED;
    }
}
