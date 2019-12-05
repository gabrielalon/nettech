<?php

namespace App\Application\User\Service;

use App\Application\User\Query;
use N3ttech\Messaging\Manager\QueryManager;
use N3ttech\Messaging\Query\Exception;

class UserQueryManager extends QueryManager
{
    /**
     * @param string $login
     * @return Query\ReadModel\Entity\User
     * @throws Exception\ResourceNotFoundException
     */
    public function findOneByLogin(string $login): Query\ReadModel\Entity\User
    {
        $query = new Query\V1\FindOneUserByLogin($login);

        $this->ask($query);

        return $query->getUser();
    }

    /**
     * @return Query\ReadModel\Entity\UserCollection
     */
    public function findAll(): Query\ReadModel\Entity\UserCollection
    {
        $query = new Query\V1\FindAllUsers();

        $this->ask($query);

        return $query->getCollection();
    }
}
