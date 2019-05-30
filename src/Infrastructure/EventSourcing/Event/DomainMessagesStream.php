<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Event;

use App\Infrastructure\Common\Exception\ImmutableArrayException;
use App\Infrastructure\Common\ImmutableArray;

class DomainMessagesStream extends ImmutableArray
{
    /**
     * Throw when the type of item is not accepted.
     *
     * @throws ImmutableArrayException
     */
    protected function guardType($item): void
    {
        if (false === $item instanceof DomainMessage) {
            throw new ImmutableArrayException('Object with invalid type given');
        }
    }
}
