<?php

namespace App\Application\User\Query\V1;

use N3ttech\Messaging\Message\Domain\Message;

class FindAllUsersHandler extends UserQueryHandler
{
    /**
     * @param FindAllUsers $query
     */
    public function run(Message $query): void
    {
        $this->query->findAllUsers($query);
    }
}
