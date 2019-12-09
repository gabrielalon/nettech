<?php

namespace App\Application\Gallery\Command;

class CreateGallery extends GalleryCommand
{
    /** @var string */
    private $name;

    /** @var string */
    private $source;

    /** @var string[] */
    private $assets;

    /**
     * CreateGallery constructor.
     * @param string $uuid
     * @param array $name
     * @param string $source
     * @param array $assets
     */
    public function __construct(
        string $uuid,
        string $name,
        string $source,
        array $assets
    ) {
        $this->setUuid($uuid);
        $this->name = $name;
        $this->source = $source;
        $this->assets = $assets;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string[]
     */
    public function getAssets(): array
    {
        return $this->assets;
    }
}
