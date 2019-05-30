<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

final class Removed implements DomainEventInterface
{
    /**
     * @var UserId
     */
    private $userId;

    public function __construct(UserId $userId)
    {
        $this->userId = $userId;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}