<?php

namespace App\Application\Gallery\Service;

use App\Application\Gallery\Query;
use N3ttech\Messaging\Manager\QueryManager;
use N3ttech\Messaging\Query\Exception;

class GalleryQueryManager extends QueryManager
{
    /**
     * @param string $uuid
     * @return Query\ReadModel\Entity\Gallery
     * @throws Exception\ResourceNotFoundException
     */
    public function findOneGalleryByUuid(string $uuid): Query\ReadModel\Entity\Gallery
    {
        $query = new Query\V1\FindOneGalleryByUuid($uuid);

        $this->ask($query);

        return $query->getGallery();
    }

    /**
     * @param string $name
     * @return Query\ReadModel\Entity\Gallery
     * @throws Exception\ResourceNotFoundException
     */
    public function findOneGalleryByName(string $name): Query\ReadModel\Entity\Gallery
    {
        $query = new Query\V1\FindOneGalleryByName($name);

        $this->ask($query);

        return $query->getGallery();
    }
}
