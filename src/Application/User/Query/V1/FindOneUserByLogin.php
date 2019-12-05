<?php

namespace App\Application\User\Query\V1;

class FindOneUserByLogin extends UserQuery
{
    /** @var string */
    private $login;

    /**
     * FindOneUserByLogin constructor.
     * @param string $login
     */
    public function __construct(string $login)
    {
        $this->login = $login;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }
}
