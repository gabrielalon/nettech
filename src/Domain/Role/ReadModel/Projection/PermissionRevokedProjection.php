<?php

namespace App\Domain\Role\ReadModel\Projection;

use App\Domain\Role\Event\PermissionRevoked;
use App\Domain\Role\ReadModel\Repository\RoleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PermissionRevokedProjection implements MessageHandlerInterface
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(PermissionRevoked $event): void
    {
        $role = $this->roleRepository->find($event->getRoleId());

        $permissions = $role->getPermissions();
        foreach ($permissions as $i => $permission) {
            if ($permission === $event->getPermission()->__toString()) {
                unset($permissions[$i]);
            }
        }

        $role->setPermissions($permissions);
        $this->roleRepository->save($role);
    }
}
