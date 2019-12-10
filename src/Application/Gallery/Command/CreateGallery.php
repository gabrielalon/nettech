<?php

namespace App\Application\Gallery\Command;

class CreateGallery extends GalleryCommand
{
    /** @var string */
    private $name;

    /** @var string */
    private $source;

    /** @var array<string> */
    private $assets;

    /**
     * CreateGallery constructor.
     *
     * @param string        $uuid
     * @param string        $name
     * @param string        $source
     * @param array<string> $assets
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
     * @return array<string>
     */
    public function getAssets(): array
    {
        return $this->assets;
    }
}
