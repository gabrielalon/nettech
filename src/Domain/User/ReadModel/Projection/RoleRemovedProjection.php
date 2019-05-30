<?php

namespace App\Domain\User\ReadModel\Projection;

use App\Domain\User\Event\RoleRemoved;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RoleRemovedProjection implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
    }

    public function __invoke(RoleRemoved $event)
    {
        $entity = $this->userRepository->find($event->getUserId());
        $entity->setRole(null);

        $this->userRepository->save($entity);
    }
}
