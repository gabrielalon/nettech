<?php

namespace App\Application\User\Query\V1;

use App\Application\User\Query\ReadModel;
use N3ttech\Messaging\Query\Exception;

abstract class UserQuery extends \N3ttech\Messaging\Query\Query\Query
{
    /** @var ReadModel\Entity\UserCollection */
    private $collection;

    /**
     * @throws Exception\ResourceNotFoundException
     *
     * @return ReadModel\Entity\User
     */
    public function getUser(): ReadModel\Entity\User
    {
        $this->initializeCollection();

        if (0 === $this->collection->count()) {
            throw new Exception\ResourceNotFoundException('User not found');
        }

        return $this->collection->current();
    }

    /**
     * @param ReadModel\Entity\User $entry
     */
    public function addUser(ReadModel\Entity\User $entry): void
    {
        $this->initializeCollection();

        $this->collection->add($entry);
    }

    /**
     * @return ReadModel\Entity\UserCollection
     */
    public function getCollection(): ReadModel\Entity\UserCollection
    {
        $this->initializeCollection();

        return $this->collection;
    }

    private function initializeCollection(): void
    {
        if (null === $this->collection) {
            $this->collection = new ReadModel\Entity\UserCollection();
        }
    }
}

