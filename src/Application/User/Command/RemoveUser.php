<?php

namespace App\Application\User\Command;

class RemoveUser extends UserCommand
{
    /**
     * RemoveUser constructor.
     *
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        $this->setUuid($uuid);
    }
}
