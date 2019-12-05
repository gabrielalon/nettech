<?php

namespace App\Application\User\Event;

use N3ttech\Messaging\Aggregate\EventBridge\AggregateChanged;
use N3ttech\Valuing as VO;

abstract class UserEvent extends AggregateChanged
{
    /**
     * @return VO\Identity\Uuid
     * @throws \Assert\AssertionFailedException
     */
    public function userUuid(): VO\Identity\Uuid
    {
        return VO\Identity\Uuid::fromIdentity($this->aggregateId());
    }
}
