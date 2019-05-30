<?php

namespace App\Domain\User\Service;

use App\Domain\User\Exception\UserDoesNotExistException;
use App\Domain\User\Query\FindUser;
use App\Domain\User\ReadModel\Entity\User;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\Common\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

class UserAsserter
{
    use HandleTrait;

    /**
     * @var MessageBusInterface
     */
    private $domainQueryBus;

    public function __construct(MessageBusInterface $domainQueryBus)
    {
        $this->domainQueryBus = $domainQueryBus;
    }

    public function assertUserExists(UserId $userId): void
    {
        /** @var User|null $user */
        $user = $this->handle($this->domainQueryBus, new FindUser($userId->getValue()));

        if (null === $user) {
            throw new UserDoesNotExistException(
                sprintf(
                'User with id %s does not exist',
                $userId->getValue()
                )
            );
        }
    }
}
