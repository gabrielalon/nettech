<?php

namespace App\Application\User\Command;

abstract class UserCommand extends \N3ttech\Messaging\Command\Command\Command
{
    /** @var string */
    private $uuid;

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }

    /**
     * @param string $uuid
     */
    protected function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }
}
