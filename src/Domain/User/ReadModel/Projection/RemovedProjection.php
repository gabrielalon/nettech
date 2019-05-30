<?php

namespace App\Domain\User\ReadModel\Projection;

use App\Domain\User\Event\Removed;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RemovedProjection implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Removed $event): void
    {
        $entity = $this->userRepository->find($event->getUserId());

        if (null === $entity) {
            throw new \LogicException('Given user not found');
        }

        $this->userRepository->remove($entity);
    }
}
