<?php

namespace App\Application\Gallery\Query\V1;

use App\Application\Gallery\Query\ReadModel;
use N3ttech\Messaging\Query\Exception;

abstract class GalleryQuery extends AbstractQuery
{
    /** @var ReadModel\Entity\GalleryCollection */
    private $collection;

    /**
     * @throws Exception\ResourceNotFoundException
     *
     * @return ReadModel\Entity\Gallery
     */
    public function getGallery(): ReadModel\Entity\Gallery
    {
        $this->initializeCollection();

        if (0 === $this->collection->count()) {
            throw new Exception\ResourceNotFoundException('Gallery not found');
        }

        return $this->collection->current();
    }

    /**
     * @param ReadModel\Entity\Gallery $entry
     */
    public function addGallery(ReadModel\Entity\Gallery $entry): void
    {
        $this->initializeCollection();

        $this->collection->add($entry);
    }

    /**
     * @return ReadModel\Entity\GalleryCollection
     */
    public function getCollection(): ReadModel\Entity\GalleryCollection
    {
        $this->initializeCollection();

        return $this->collection;
    }

    private function initializeCollection(): void
    {
        if (null === $this->collection) {
            $this->collection = new ReadModel\Entity\GalleryCollection();
        }
    }
}


