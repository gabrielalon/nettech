<?php

namespace App\Application\User\Query;

interface UserQuery
{
    /**
     * @param V1\FindOneUserByLogin $query
     */
    public function findOneByLogin(V1\FindOneUserByLogin $query): void;

    /**
     * @param V1\FindAllUsers $query
     */
    public function findAllUsers(V1\FindAllUsers $query): void;
}
