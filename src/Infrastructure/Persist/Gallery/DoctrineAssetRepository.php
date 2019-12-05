<?php

namespace App\Infrastructure\Persist\Gallery;

use App\Application\Gallery\Query\ReadModel\Entity\Asset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineAssetRepository extends ServiceEntityRepository
{
    /**
     * DoctrineAssetRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Asset::class);
    }
}