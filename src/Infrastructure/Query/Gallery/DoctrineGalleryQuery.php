<?php

namespace App\Infrastructure\Query\Gallery;

use App\Application\Gallery\Query;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineGalleryQuery extends DoctrineQuery implements Query\GalleryQuery
{
    /**
     * DoctrineGalleryQuery constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Query\ReadModel\Entity\Gallery::class);
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByUuid(Query\V1\FindOneGalleryByUuid $query): void
    {
        if (null !== $gallery = $this->find($query->getUuid())) {
            /* @var Query\ReadModel\Entity\Gallery $gallery */
            $query->addGallery($gallery);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function findOneByName(Query\V1\FindOneGalleryByName $query): void
    {
        if (null !== $gallery = $this->findOneBy(['name' => $query->getName()])) {
            /* @var Query\ReadModel\Entity\Gallery $gallery */
            $query->addGallery($gallery);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function listAllBySource(Query\V1\ListAllGalleryBySource $query): void
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT SQL_CALC_FOUND_ROWS g.*, count(ga.id) AS asset_counter
            FROM `gallery` g
            LEFT JOIN `gallery_asset` ga ON ga.`gallery_uuid` = g.`uuid`
            WHERE g.`source` = :source
            GROUP BY g.`uuid`
            ORDER BY {order_field} {order_sort}
            LIMIT {limit} OFFSET {offset}
        ';

        $this->injectParameter($sql, 'order_field', $query->getOrderField());
        $this->injectParameter($sql, 'order_sort', $query->getOrderSort());
        $this->injectParameter($sql, 'limit', $query->getLimit());
        $this->injectParameter($sql, 'offset', $query->getOffset());

        $statement = $connection->prepare($sql);
        $statement->execute(['source' => $query->getSource()]);

        /** @var Query\ReadModel\Entity\Gallery $gallery */
        foreach ($statement->fetchAll(\PDO::FETCH_CLASS, Query\ReadModel\Entity\Gallery::class) as $gallery) {
            $query->addGallery($gallery);
        }

        $query->setTotalCounter($this->calculateTotalRows());
    }
}
