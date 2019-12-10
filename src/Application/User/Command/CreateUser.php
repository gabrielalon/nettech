<?php

namespace App\Application\User\Command;

class CreateUser extends UserCommand
{
    /** @var string */
    private $login;

    /** @var string */
    private $password;

    /**
     * CreateUser constructor.
     *
     * @param string $uuid
     * @param string $login
     * @param string $password
     */
    public function __construct(string $uuid, string $login, string $password)
    {
        $this->setUuid($uuid);
        $this->login = $login;
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
