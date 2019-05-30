<?php

namespace App\Domain\Role\Collection;

use App\Domain\Role\Exception\PermissionAlreadyAssignedException;
use App\Domain\Role\Exception\PermissionNotFoundException;
use App\Domain\Role\ValueObject\Permission;
use Assert\Assertion;

class PermissionCollection implements \IteratorAggregate, \Countable, \JsonSerializable
{
    /**
     * @var array
     */
    private $collection;

    /**
     * @throws \InvalidArgumentException
     */
    public function __construct(array $collection)
    {
        Assertion::allIsInstanceOf($collection, Permission::class);
        $this->collection = $collection;
    }

    /**
     * @throws PermissionAlreadyAssignedException
     */
    public function assign(Permission $permission): void
    {
        $this->assertPermissionNotExists($permission);
        $this->collection[] = $permission;
    }

    private function assertPermissionNotExists(Permission $permission): void
    {
        foreach ($this->collection as $internalPermission) {
            if ($internalPermission->equals($permission)) {
                throw new PermissionAlreadyAssignedException(
                    sprintf('Permission %s is already assigned', $permission->__toString())
                );
            }
        }
    }

    /**
     * @throws PermissionNotFoundException
     */
    public function revoke(Permission $permission): void
    {
        $this->assertPermissionExists($permission);

        foreach ($this->collection as $i => $internalPermission) {
            if ($internalPermission->equals($permission)) {
                unset($this->collection[$i]);
            }
        }
    }

    public function assertPermissionExists(Permission $permission): void
    {
        $found = false;
        foreach ($this->collection as $internalPermission) {
            if ($internalPermission->equals($permission)) {
                $found = true;
            }
        }

        if (false === $found) {
            throw new PermissionNotFoundException(
                sprintf('Permission %s not found', $permission->__toString())
            );
        }
    }

    public function toArray(): array
    {
        return $this->collection;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->collection);
    }

    public function count()
    {
        return \count($this->collection);
    }

    public function jsonSerialize()
    {
        return $this->collection;
    }
}
