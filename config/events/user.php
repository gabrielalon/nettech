<?php

use App\Application\User\Event;

return [
    Event\ExistingUserRemoved::class => [\App\Domain\Model\User\Projection\UserProjector::class],
    Event\NewUserCreated::class => [\App\Domain\Model\User\Projection\UserProjector::class],
];
