<?php

namespace App\Infrastructure\Query\User;

use App\Application\User\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineUserQuery extends ServiceEntityRepository implements Query\UserQuery
{
    /**
     * DoctrineGalleryQuery constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Query\ReadModel\Entity\User::class);
    }

    /**
     * @inheritDoc
     */
    public function findOneByLogin(Query\V1\FindOneUserByLogin $query): void
    {
        if (null !== $user = $this->findOneBy(['login' => $query->getLogin()])) {
            /** @var Query\ReadModel\Entity\User $user */
            $query->addUser($user);
        }
    }

    /**
     * @inheritDoc
     */
    public function findAllUsers(Query\V1\FindAllUsers $query): void
    {
        /** @var Query\ReadModel\Entity\User $user */
        foreach ($this->findAll() as $user) {
            $query->addUser($user);
        }
    }
}
