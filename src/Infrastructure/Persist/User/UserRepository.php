<?php

namespace App\Infrastructure\Persist\User;

use App\Domain\Model\User\User;
use N3ttech\Messaging\Aggregate\AggregateRoot;
use N3ttech\Messaging\Aggregate\Persist\AggregateRepository;
use N3ttech\Valuing as VO;

class UserRepository extends AggregateRepository
{
    /**
     * {@inheritdoc}
     */
    public function getAggregateRootClass(): string
    {
        return User::class;
    }

    /**
     * @param User $user
     *
     * @throws \Exception
     */
    public function save(User $user): void
    {
        $this->saveAggregateRoot($user);
    }

    /**
     * @param string $uuid
     *
     * @return AggregateRoot|User
     * @throws \Assert\AssertionFailedException
     *
     */
    public function find(string $uuid): AggregateRoot
    {
        return $this->findAggregateRoot(VO\Identity\Uuid::fromIdentity($uuid));
    }
}
