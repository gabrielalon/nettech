<?php

namespace App\Infrastructure\Query\Gallery;

use App\Application\Gallery\Query;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineGalleryQuery extends ServiceEntityRepository implements Query\GalleryQuery
{
    /**
     * DoctrineGalleryQuery constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Query\ReadModel\Entity\Gallery::class);
    }

    /**
     * @inheritDoc
     */
    public function findAllPaginated(Query\V1\FindAllGalleriesPaginated $query): void
    {

    }

    /**
     * @inheritDoc
     */
    public function findOneByUuid(Query\V1\FindOneGalleryByUuid $query): void
    {
        if (null !== $gallery = $this->find($query->getUuid())) {
            /** @var Query\ReadModel\Entity\Gallery $gallery */
            $query->addGallery($gallery);
        }
    }
}
