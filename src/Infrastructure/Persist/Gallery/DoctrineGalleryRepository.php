<?php

namespace App\Infrastructure\Persist\Gallery;

use App\Application\Gallery\Query\ReadModel\Entity\Gallery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineGalleryRepository extends ServiceEntityRepository
{
    /**
     * DoctrineGalleryRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gallery::class);
    }
}