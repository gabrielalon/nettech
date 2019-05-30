<?php

namespace App\Domain\User\ReadModel\Projection;

use App\Domain\User\Event\PasswordHashChanged;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class PasswordHashChangedProjection implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(PasswordHashChanged $event)
    {
        $entity = $this->userRepository->find($event->getUserId());
        $entity->setPasswordHash($event->getPasswordHash()->getValue());

        $this->userRepository->save($entity);
    }
}
