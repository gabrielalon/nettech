<?php

namespace App\Application\Gallery\Query;

interface GalleryQuery
{
    /**
     * @param V1\FindOneGalleryByUuid $query
     */
    public function findOneByUuid(V1\FindOneGalleryByUuid $query): void;

    /**
     * @param V1\FindOneGalleryByName $query
     */
    public function findOneByName(V1\FindOneGalleryByName $query): void;

    /**
     * @param v1\ListAllGalleryBySource $query
     */
    public function listAllBySource(V1\ListAllGalleryBySource $query): void;
}
