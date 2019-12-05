<?php

namespace App\Application\Gallery\Service;

use App\Application\Gallery\Query;
use N3ttech\Messaging\Manager\QueryManager;

class GalleryQueryManager extends QueryManager
{
    /**
     * @param string $uuid
     * @return Query\ReadModel\Entity\Gallery
     */
    public function findOneGalleryByUuid(string $uuid): Query\ReadModel\Entity\Gallery
    {
        $query = new Query\V1\FindOneGalleryByUuid($uuid);

        $this->ask($query);

        return $query->getGallery();
    }

    /**
     * @param int $page
     * @param int $limit
     * @return Query\ReadModel\Pagination
     */
    public function paginatedGalleries(int $page = 1, int $limit = 5): Query\ReadModel\Pagination
    {
        $query = new Query\V1\FindAllGalleriesPaginated($page, $limit);

        $this->ask($query);

        return new Query\ReadModel\Pagination($page, $query->getTotal(), $query->getCollection());
    }

    /**
     * @param string $uuid
     * @param int $page
     * @param int $limit
     * @return Query\ReadModel\Pagination
     */
    public function paginatedAssets(string $uuid, int $page = 1, int $limit = 5): Query\ReadModel\Pagination
    {
        $query = new Query\V1\FindAllGalleryAssetsPaginated($uuid, $page, $limit);

        $this->ask($query);

        return new Query\ReadModel\Pagination($page, $query->getTotal(), $query->getCollection());
    }
}
