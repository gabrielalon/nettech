<?php

namespace App\Domain\User\Query;

/**
 * TODO Do usunięcia razem z handlerem, nieużywane query.
 */
class FindUserByLogin
{
    /**
     * @var string
     */
    private $login;

    public function __construct(string $login)
    {
        $this->login = $login;
    }

    public function getLogin(): string
    {
        return $this->login;
    }
}
