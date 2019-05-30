<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\Login;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

final class LoginChanged implements DomainEventInterface
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var Login
     */
    private $login;

    public function __construct(UserId $userId, Login $login)
    {
        $this->userId = $userId;
        $this->login = $login;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
