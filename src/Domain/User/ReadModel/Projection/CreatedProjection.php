<?php

namespace App\Domain\User\ReadModel\Projection;

use App\Domain\User\Event\Created;
use App\Domain\User\ReadModel\Entity\User;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreatedProjection implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(Created $event): void
    {
        $this->userRepository->save(new User(
            $event->getUserId(),
            $event->getFirstName()->getValue(),
            $event->getLastName()->getValue(),
            $event->getLogin()->getValue(),
            $event->getPasswordHash()->getValue(),
            null,
            new \DateTimeImmutable('now')
        ));
    }
}
