<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\FirstName;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

final class FirstNameChanged implements DomainEventInterface
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var FirstName
     */
    private $firstName;

    public function __construct(UserId $userId, FirstName $firstName)
    {
        $this->userId = $userId;
        $this->firstName = $firstName;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
