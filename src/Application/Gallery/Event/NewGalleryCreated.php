<?php

namespace App\Application\Gallery\Event;

use App\Domain\Model\Gallery\Gallery;
use N3ttech\Messaging\Aggregate\AggregateRoot;
use N3ttech\Valuing as VO;

class NewGalleryCreated extends GalleryEvent
{
    /**
     * @return VO\Date\Time
     *
     * @throws \Assert\AssertionFailedException
     */
    public function galleryCreationDate(): VO\Date\Time
    {
        return VO\Date\Time::fromTimestamp($this->payload['creation_date']);
    }

    /**
     * @return VO\Char\Text
     *
     * @throws \Assert\AssertionFailedException
     */
    public function galleryName(): VO\Char\Text
    {
        return VO\Char\Text::fromString($this->payload['name']);
    }

    /**
     * @return VO\Char\Text
     *
     * @throws \Assert\AssertionFailedException
     */
    public function gallerySource(): VO\Char\Text
    {
        return VO\Char\Text::fromString($this->payload['source']);
    }

    /**
     * @return Gallery\Assets
     *
     * @throws \Assert\AssertionFailedException
     */
    public function galleryAssets(): Gallery\Assets
    {
        return Gallery\Assets::fromArray($this->payload['assets'] ?? []);
    }

    /**
     * @param Gallery $gallery
     *
     * @throws \Assert\AssertionFailedException
     */
    public function populate(AggregateRoot $gallery): void
    {
        $gallery->setUuid($this->galleryUuid());
        $gallery->setCreationDate($this->galleryCreationDate());
        $gallery->setName($this->galleryName());
        $gallery->setSource($this->gallerySource());
        $gallery->setAssets($this->galleryAssets());
    }
}
