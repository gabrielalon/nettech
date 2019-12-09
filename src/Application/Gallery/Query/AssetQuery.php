<?php

namespace App\Application\Gallery\Query;

interface AssetQuery
{
    /**
     * @param V1\ListAllAssetByGallery $query
     */
    public function listAllAssetByGallery(V1\ListAllAssetByGallery $query): void;
}
