<?php

namespace App\Application\User\Event;

use App\Domain\Model\User\User;
use N3ttech\Messaging\Aggregate\AggregateRoot;

class ExistingUserRemoved  extends UserEvent
{
    /**
     * @param User $user
     * @throws \Assert\AssertionFailedException
     */
    public function populate(AggregateRoot $user): void
    {
        $user->setUuid($this->userUuid());
    }
}
