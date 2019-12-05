<?php

namespace App\Application\User\Query\V1;

use App\Application\User\Query\UserQuery;

abstract class UserQueryHandler implements \N3ttech\Messaging\Query\QueryHandling\QueryHandler
{
    /** @var UserQuery */
    protected $query;

    /**
     * @param UserQuery $query
     */
    public function __construct(UserQuery $query)
    {
        $this->query = $query;
    }
}
