<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

final class PasswordHashChanged implements DomainEventInterface
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var Password
     */
    private $passwordHash;

    public function __construct(UserId $userId, Password $passwordHash)
    {
        $this->userId = $userId;
        $this->passwordHash = $passwordHash;
    }

    public function getPasswordHash(): Password
    {
        return $this->passwordHash;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
