<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Aggregate;

use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;

interface TracksChangesInterface
{
    public function getAggregateId(): IdentifyInterface;

    public function getPlayHead(): int;
}
