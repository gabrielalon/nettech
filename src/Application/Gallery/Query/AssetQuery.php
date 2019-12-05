<?php

namespace App\Application\Gallery\Query;

interface AssetQuery
{
    /**
     * @param V1\FindAllGalleryAssetsPaginated $query
     */
    public function findAllPaginated(V1\FindAllGalleryAssetsPaginated $query): void;
}
