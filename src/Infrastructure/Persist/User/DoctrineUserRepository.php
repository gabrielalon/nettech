<?php

namespace App\Infrastructure\Persist\User;

use App\Application\User\Query\ReadModel\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineUserRepository extends ServiceEntityRepository
{
    /**
     * DoctrineUserRepository constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }
}
