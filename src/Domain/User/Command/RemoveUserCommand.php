<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

final class RemoveUserCommand
{
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
