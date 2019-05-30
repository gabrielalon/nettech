<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Shared;

interface IdentifyInterface
{
    /**
     * @return string
     */
    public function __toString();

    /**
     * @param $other
     */
    public function equals(self $other): bool;
}
