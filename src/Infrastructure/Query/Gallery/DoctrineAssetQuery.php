<?php

namespace App\Infrastructure\Query\Gallery;

use App\Application\Gallery\Query;
use Doctrine\Common\Persistence\ManagerRegistry;

class DoctrineAssetQuery extends DoctrineQuery implements Query\AssetQuery
{
    /**
     * DoctrineGalleryQuery constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Query\ReadModel\Entity\Asset::class);
    }

    /**
     * @inheritDoc
     */
    public function listAllAssetByGallery(Query\V1\ListAllAssetByGallery $query): void
    {
        $connection = $this->getEntityManager()->getConnection();

        $sql = '
            SELECT SQL_CALC_FOUND_ROWS *
            FROM `gallery_asset`
            WHERE `gallery_uuid` = :gallery_uuid
            ORDER BY {order_field} {order_sort}
            LIMIT {limit} OFFSET {offset}
        ';

        $this->injectParameter($sql, 'order_field', $query->getOrderField());
        $this->injectParameter($sql, 'order_sort', $query->getOrderSort());
        $this->injectParameter($sql, 'limit', $query->getLimit());
        $this->injectParameter($sql, 'offset', $query->getOffset());

        $statement = $connection->prepare($sql);
        $statement->execute(['gallery_uuid' => $query->getGalleryUuid()]);

        /** @var Query\ReadModel\Entity\Asset $asset */
        foreach ($statement->fetchAll(\PDO::FETCH_CLASS, Query\ReadModel\Entity\Asset::class) as $asset) {
            $query->addAsset($asset);
        }

        $query->setTotalCounter($this->calculateTotalRows());
    }
}
