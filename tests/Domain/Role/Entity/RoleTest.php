<?php

namespace App\Tests\Domain\Role\Entity;

use App\Domain\Role\Collection\PermissionCollection;
use App\Domain\Role\Entity\Role;
use App\Domain\Role\Event\Created;
use App\Domain\Role\Event\PermissionAssigned;
use App\Domain\Role\Event\PermissionRevoked;
use App\Domain\Role\Event\Removed;
use App\Domain\Role\Exception\PermissionNotFoundException;
use App\Domain\Role\Exception\RoleRemovedException;
use App\Domain\Role\ValueObject\Name;
use App\Domain\Role\ValueObject\Permission;
use App\Domain\Role\ValueObject\RoleId;
use App\Tests\Infrastructure\EventSourcing\Aggregate\AggregateRootScenarioTestCase;
use Ramsey\Uuid\Uuid;

class RoleTest extends AggregateRootScenarioTestCase
{
    protected function getAggregateRootClass(): string
    {
        return Role::class;
    }

    public function testCreated()
    {
        $roleId = new RoleId(Uuid::uuid4());

        $this->scenario
            ->when(function () use ($roleId) {
                return new Role(
                    $roleId,
                    new Name('kierownik'),
                    new PermissionCollection([
                        new Permission('change', 'cos', true),
                    ])
                );
            })
            ->then([
                new Created(
                    $roleId,
                    new Name('kierownik'),
                    new PermissionCollection([
                        new Permission('change', 'cos', true),
                    ]),
                    new \DateTimeImmutable('@' . time())
                ),
            ]);
    }

    public function testAssaignPermission()
    {
        $roleId = new RoleId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($roleId) {
                $role = new Role(
                    $roleId,
                    new Name('jakasNazwa'),
                    new PermissionCollection([
                        new Permission('add', 'dodaje', true),
                        new Permission('remove', 'usun', false),
                    ])
                );

                return $role;
            })
            ->when(function (Role $role) {
                $role->assignPermission(
                    new Permission('login', 'loguje', true)
                );
            })
            ->then([
                new PermissionAssigned(
                    $roleId,
                    new Permission('login', 'loguje', true)
                ),
            ])
            ->thenCallable(function (Role $role) {
                $this->assertSame(new Name('jakasNazwa'), $role->getName());
            });
    }

    public function testAssaignPermissionOnRemovedRole()
    {
        $roleId = new RoleId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($roleId) {
                $role = new Role(
                    $roleId,
                    new Name('mojarola'),
                    new PermissionCollection([
                        new Permission('add', 'dodaje', true),
                        new Permission('remove', 'usun', false),
                    ])
                );

                $role->remove();

                return $role;
            })
            ->when(function (Role $role) {
                $role->assignPermission(
                    new Permission('login', 'loguje', true)
                );
            })
            ->thenExceptionThrown(RoleRemovedException::class);
    }

    public function testRevokePermission()
    {
        $roleId = new RoleId(Uuid::uuid4());
        $permission = new Permission('remove', 'usun', false);

        $this->scenario
            ->givenCallable(function () use ($roleId, $permission) {
                $role = new Role(
                    $roleId,
                    new Name('costamcos'),
                    new PermissionCollection([
                        new Permission('add', 'dodaje', true),
                        $permission,
                    ])
                );

                return $role;
            })
            ->when(function (Role $role) use ($permission) {
                $role->revokePermission(
                    $permission
                );
            })
            ->then([
                new PermissionRevoked(
                    $roleId,
                    $permission
                ),
            ])
            ->thenCallable(function (Role $role) {
                $this->assertSame(new Name('costamcos'), $role->getName());
            });
    }

    public function testRevokePermissionOnRemovedRole()
    {
        $roleId = new RoleId(Uuid::uuid4());
        $permission = new Permission('remove', 'usun', false);

        $this->scenario
            ->givenCallable(function () use ($roleId, $permission) {
                $role = new Role(
                    $roleId,
                    new Name('jakasNazwa'),
                    new PermissionCollection([
                        new Permission('add', 'dodaje', true),
                        $permission,
                    ])
                );

                $role->remove();

                return $role;
            })
            ->when(function (Role $role) use ($permission) {
                $role->revokePermission(
                    $permission
                );
            })
            ->thenExceptionThrown(RoleRemovedException::class);
    }

    public function testRevokeNotExistingPermission()
    {
        $roleId = new RoleId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($roleId) {
                $role = new Role(
                    $roleId,
                    new Name('jakasNazwa'),
                    new PermissionCollection([
                        new Permission('add', 'dodaje', true),
                        new Permission('remove', 'usun', false),
                    ])
                );

                return $role;
            })
            ->when(function (Role $role) {
                $role->revokePermission(
                    new Permission('cos', 'cos', false)
                );
            })
            ->thenExceptionThrown(PermissionNotFoundException::class);
    }

    public function testRemove()
    {
        $roleId = new RoleId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($roleId) {
                $role = new Role(
                    $roleId,
                    new Name('blabla'),
                    new PermissionCollection([
                        new Permission('add', 'dodaje', true),
                        new Permission('remove', 'usun', false),
                    ])
                );

                return $role;
            })
            ->when(function (Role $role) {
                $role->remove();
            })
            ->then([
                new Removed(
                    $roleId
                ),
            ]);
    }

    public function testRemoveRemoved()
    {
        $roleId = new RoleId(Uuid::uuid4());

        $this->scenario
            ->givenCallable(function () use ($roleId) {
                $role = new Role(
                    $roleId,
                    new Name('blabla'),
                    new PermissionCollection([
                        new Permission('add', 'dodaje', true),
                        new Permission('remove', 'usun', false),
                    ])
                );

                $role->remove();

                return $role;
            })
            ->when(function (Role $role) {
                $role->remove();
            })
            ->thenExceptionThrown(RoleRemovedException::class);
    }
}
