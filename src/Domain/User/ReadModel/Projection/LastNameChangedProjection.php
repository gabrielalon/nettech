<?php

namespace App\Domain\User\ReadModel\Projection;

use App\Domain\User\Event\LastNameChanged;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class LastNameChangedProjection implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(LastNameChanged $event)
    {
        $entity = $this->userRepository->find($event->getUserId());
        $entity->setLastName($event->getLastName()->getValue());

        $this->userRepository->save($entity);
    }
}
