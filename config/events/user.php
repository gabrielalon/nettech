<?php

use App\Application\User\Event;

return [
    Event\ExistingUserRemoved::class => [\App\Infrastructure\Projection\User\DoctrineUserProjector::class],
    Event\NewUserCreated::class => [\App\Infrastructure\Projection\User\DoctrineUserProjector::class],
];
