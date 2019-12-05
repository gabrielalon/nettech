<?php

namespace App\Infrastructure\Query\Gallery;

use App\Application\Gallery\Query;
use App\Infrastructure\Doctrine\DatabaseConnected;

class DoctrineAssetQuery extends DatabaseConnected implements Query\AssetQuery
{
    /**
     * @inheritDoc
     */
    public function findAllPaginated(Query\V1\FindAllGalleryAssetsPaginated $query): void
    {
        // TODO: Implement findAllPaginated() method.
    }
}
