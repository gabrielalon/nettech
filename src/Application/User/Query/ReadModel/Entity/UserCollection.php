<?php

namespace App\Application\User\Query\ReadModel\Entity;

use N3ttech\Messaging\Query\Query;

class UserCollection extends \ArrayIterator implements Query\ViewableCollection
{
    /**
     * @param User $user
     */
    public function add(Query\Viewable $user): void
    {
        $this->offsetSet($user->identifier(), $user);
    }

    /**
     * @param string $uuid
     *
     * @return User
     */
    public function get(string $uuid): User
    {
        return $this->offsetGet($uuid);
    }

    /**
     * @param string $uuid
     *
     * @return bool
     */
    public function has(string $uuid): bool
    {
        return $this->offsetExists($uuid);
    }

    /**
     * @param string $uuid
     */
    public function remove(string $uuid): void
    {
        $this->offsetUnset($uuid);
    }

    /**
     * @return User[]
     */
    public function all(): array
    {
        return $this->getArrayCopy();
    }
}
