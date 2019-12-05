<?php

namespace App\Application\User\Query\V1;

use N3ttech\Messaging\Message\Domain\Message;

class FindOneUserByLoginHandler extends UserQueryHandler
{
    /**
     * @param FindOneUserByLogin $query
     */
    public function run(Message $query): void
    {
        $this->query->findOneByLogin($query);
    }
}
