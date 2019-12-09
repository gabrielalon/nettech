<?php

namespace App\Tests\Infrastructure\Query\Gallery;

use App\Application\Gallery\Query;

class InMemoryGalleryQuery implements Query\GalleryQuery
{
    /** @var Query\ReadModel\Entity\GalleryCollection */
    private $galleries;

    /**
     * @param Query\ReadModel\Entity\GalleryCollection|null $galleries
     */
    public function __construct(Query\ReadModel\Entity\GalleryCollection $galleries = null)
    {
        if (null === $galleries) {
            $galleries = new Query\ReadModel\Entity\GalleryCollection([]);
        }

        $this->galleries = $galleries;
    }

    /**
     * @inheritDoc
     */
    public function findOneByUuid(Query\V1\FindOneGalleryByUuid $query): void
    {
        $this->galleries->rewind();
        $query->addGallery($this->galleries->current());
    }

    /**
     * @inheritDoc
     */
    public function findOneByName(Query\V1\FindOneGalleryByName $query): void
    {
        $this->galleries->rewind();
        $query->addGallery($this->galleries->current());
    }

    /**
     * @inheritDoc
     */
    public function listAllBySource(Query\V1\ListAllGalleryBySource $query): void
    {
        // TODO: Implement listAllBySource() method.
    }
}