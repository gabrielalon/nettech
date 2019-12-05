<?php

namespace App\Application\Gallery\Query;

interface GalleryQuery
{
    /**
     * @param V1\FindAllGalleriesPaginated $query
     */
    public function findAllPaginated(V1\FindAllGalleriesPaginated $query): void;

    /**
     * @param V1\FindOneGalleryByUuid $query
     */
    public function findOneByUuid(V1\FindOneGalleryByUuid $query): void;
}
