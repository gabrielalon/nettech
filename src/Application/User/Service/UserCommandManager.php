<?php

namespace App\Application\User\Service;

use App\Application\User\Command;
use N3ttech\Messaging\Manager\CommandManager;

class UserCommandManager extends CommandManager
{
    /**
     * @param string $uuid
     * @param string $login
     * @param string $password
     */
    public function create(string $uuid, string $login, string $password): void
    {
        $this->command(new Command\CreateUser($uuid, $login, $password));
    }

    /**
     * @param string $uuid
     */
    public function remove(string $uuid): void
    {
        $this->command(new Command\RemoveUser($uuid));
    }
}
