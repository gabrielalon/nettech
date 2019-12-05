<?php

namespace App\Domain\Model\User\Projection;

use App\Application\User\Event;
use N3ttech\Messaging\Message\EventSourcing\EventProjector;

interface UserProjector extends EventProjector
{
    /**
     * @param Event\NewUserCreated $event
     */
    public function onNewUserCreated(Event\NewUserCreated $event): void;

    /**
     * @param Event\ExistingUserRemoved $event
     */
    public function onExistingUserRemoved(Event\ExistingUserRemoved $event): void;
}
