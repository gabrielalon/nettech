<?php

namespace App\Tests\Infrastructure\Query\User;

use App\Application\User\Query;

final class InMemoryUserQuery implements Query\UserQuery
{
    /** @var Query\ReadModel\Entity\UserCollection */
    private $users;

    /**
     * @param Query\ReadModel\Entity\UserCollection|null $users
     */
    public function __construct(Query\ReadModel\Entity\UserCollection $users = null)
    {
        if (null === $users) {
            $users = new Query\ReadModel\Entity\UserCollection([]);
        }

        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByLogin(Query\V1\FindOneUserByLogin $query): void
    {
        $this->users->rewind();
        $query->addUser($this->users->current());
    }

    /**
     * {@inheritdoc}
     */
    public function findAllUsers(Query\V1\FindAllUsers $query): void
    {
        foreach ($this->users->getArrayCopy() as $user) {
            $query->addUser($user);
        }
    }
}
