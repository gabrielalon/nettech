<?php

namespace App\Domain\User\ReadModel\Projection;

use App\Domain\User\Event\FirstNameChanged;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FirstNameChangedProjection implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(FirstNameChanged $event)
    {
        $entity = $this->userRepository->find($event->getUserId());
        $entity->setFirstName($event->getFirstName()->getValue());

        $this->userRepository->save($entity);
    }
}
