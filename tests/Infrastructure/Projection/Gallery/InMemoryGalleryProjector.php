<?php

namespace App\Tests\Infrastructure\Projection\Gallery;

use App\Application\Gallery\Event;
use App\Application\Gallery\Query\ReadModel;
use App\Domain\Model\Gallery\Asset;
use App\Domain\Model\Gallery\Projection\GalleryProjector;

class InMemoryGalleryProjector implements GalleryProjector
{
    /** @var ReadModel\Entity\GalleryCollection */
    private $galleries;

    /**
     * @param ReadModel\Entity\GalleryCollection|null $galleries
     */
    public function __construct(ReadModel\Entity\GalleryCollection $galleries = null)
    {
        if (null === $galleries) {
            $galleries = new ReadModel\Entity\GalleryCollection([]);
        }

        $this->galleries = $galleries;
    }

    /**
     * @inheritDoc
     */
    public function onNewGalleryCreated(Event\NewGalleryCreated $event): void
    {
        $gallery = new ReadModel\Entity\Gallery();
        $gallery->setUuid($event->galleryUuid()->toString());
        $gallery->setSource($event->gallerySource()->toString());
        $gallery->setName($event->galleryName()->toString());

        $this->galleries->add($gallery);
    }

    /**
     * @param string $uuid
     *
     * @return ReadModel\Entity\Gallery
     *
     * @throws \RuntimeException
     */
    public function get(string $uuid): ReadModel\Entity\Gallery
    {
        $this->checkExistence($uuid);

        return $this->galleries->get($uuid);
    }

    /**
     * @param string $uuid
     *
     * @throws \RuntimeException
     */
    private function checkExistence(string $uuid): void
    {
        if (false === $this->galleries->has($uuid)) {
            throw new \RuntimeException(\sprintf('Gallery does not exists on given uuid: %s', $uuid));
        }
    }
}