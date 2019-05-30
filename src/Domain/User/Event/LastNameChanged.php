<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\LastName;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

final class LastNameChanged implements DomainEventInterface
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var LastName
     */
    private $lastName;

    public function __construct(UserId $userId, LastName $lastName)
    {
        $this->userId = $userId;
        $this->lastName = $lastName;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
