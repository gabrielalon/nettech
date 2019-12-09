<?php

namespace App\Application\Gallery\Query\V1;

class FindOneGalleryByName extends GalleryQuery
{
    /** @var string */
    private $name;

    /**
     * FindOneGalleryByName constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
