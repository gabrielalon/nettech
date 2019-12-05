<?php

namespace App\Domain\Model\Gallery;

use App\Application\Gallery\Event;
use N3ttech\Messaging\Aggregate\AggregateRoot;
use N3ttech\Valuing as VO;

class Gallery extends AggregateRoot
{
    /** @var VO\Date\Time */
    private $creationDate;

    /** @var VO\Char\Text */
    private $name;

    /** @var VO\Char\Text */
    private $source;

    /** @var Gallery\Assets */
    private $assets;

    /**
     * @param VO\Identity\Uuid $uuid
     * @return Gallery
     */
    public function setUuid(VO\Identity\Uuid $uuid): Gallery
    {
        $this->setAggregateId($uuid);

        return $this;
    }

    /**
     * @param VO\Date\Time $creationDate
     * @return Gallery
     */
    public function setCreationDate(VO\Date\Time $creationDate): Gallery
    {
        $this->creationDate = $creationDate;
        return $this;
    }

    /**
     * @param VO\Char\Text $name
     * @return Gallery
     */
    public function setName(VO\Char\Text $name): Gallery
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @param VO\Char\Text $source
     * @return Gallery
     */
    public function setSource(VO\Char\Text $source): Gallery
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @param Gallery\Assets $assets
     * @return Gallery
     */
    public function setAssets(Gallery\Assets $assets): Gallery
    {
        $this->assets = $assets;
        return $this;
    }

    /**
     * @param VO\Identity\Uuid $uuid
     * @param VO\Char\Text $name
     * @param VO\Char\Text $source
     * @param Gallery\Assets $assets
     * @return Gallery
     */
    public static function createNewGallery(
        VO\Identity\Uuid $uuid,
        VO\Char\Text $name,
        VO\Char\Text $source,
        Gallery\Assets $assets
    ): Gallery {
        $gallery = new Gallery();

        $gallery->recordThat(Event\NewGalleryCreated::occur($uuid->toString(), [
            'creation_date' => time(),
            'name' => $name->toString(),
            'source' => $source->toString(),
            'assets' => $assets->toArray()
        ]));

        return $gallery;
    }
}
