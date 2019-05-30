<?php

namespace App\Domain\Role\ReadModel\Projection;

use App\Domain\Role\Event\Removed;
use App\Domain\Role\ReadModel\Repository\RoleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RemovedProjection implements MessageHandlerInterface
{
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(Removed $event)
    {
        $role = $this->roleRepository->find($event->getRoleId()->__toString());
        $this->roleRepository->remove($role);
    }
}
