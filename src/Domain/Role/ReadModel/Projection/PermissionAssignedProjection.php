<?php

namespace App\Domain\Role\ReadModel\Projection;

use App\Domain\Role\Event\PermissionAssigned;
use App\Domain\Role\ReadModel\Repository\RoleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PermissionAssignedProjection implements MessageHandlerInterface
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(PermissionAssigned $event): void
    {
        $role = $this->roleRepository->find($event->getRoleId());

        $permissions = $role->getPermissions();
        $permissions[] = $event->getPermission()->__toString();
        $role->setPermissions($permissions);

        $this->roleRepository->save($role);
    }
}
