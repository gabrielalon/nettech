<?php

namespace App\Application\Gallery\Query\V1;

class FindOneGalleryByUuid extends GalleryQuery
{
    /** @var string */
    private $uuid;

    /**
     * FindOneGalleryByUuid constructor.
     * @param string $uuid
     */
    public function __construct(string $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
