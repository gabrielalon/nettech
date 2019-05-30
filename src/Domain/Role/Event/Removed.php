<?php

namespace App\Domain\Role\Event;

use App\Domain\Role\ValueObject\RoleId;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

final class Removed implements DomainEventInterface
{
    /**
     * @var RoleId
     */
    private $roleId;

    public function __construct(RoleId $roleId)
    {
        $this->roleId = $roleId;
    }

    public function getRoleId(): RoleId
    {
        return $this->roleId;
    }
}
