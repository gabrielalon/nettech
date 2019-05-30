<?php

namespace App\Domain\User\ReadModel\Projection;

use App\Domain\Role\ReadModel\Repository\RoleRepositoryInterface;
use App\Domain\User\Event\RoleAssigned;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RoleAssignedProjection implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    public function __construct(
        UserRepositoryInterface $userRepository,
        RoleRepositoryInterface $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(RoleAssigned $event)
    {
        $user = $this->userRepository->find($event->getUserId());
        $role = $this->roleRepository->find($event->getRoleId());
        $user->setRole($role);

        $this->userRepository->save($user);
    }
}
