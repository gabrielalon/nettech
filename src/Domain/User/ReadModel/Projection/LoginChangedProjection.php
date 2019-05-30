<?php

namespace App\Domain\User\ReadModel\Projection;

use App\Domain\User\Event\LoginChanged;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class LoginChangedProjection implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(LoginChanged $event)
    {
        $entity = $this->userRepository->find($event->getUserId());
        $entity->setLogin($event->getLogin()->getValue());

        $this->userRepository->save($entity);
    }
}
