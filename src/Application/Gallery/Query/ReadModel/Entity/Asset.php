<?php

namespace App\Application\Gallery\Query\ReadModel\Entity;

use Doctrine\ORM\Mapping as ORM;
use N3ttech\Messaging\Query\Query;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persist\Gallery\DoctrineAssetRepository")
 * @ORM\Table(
 *     name="gallery_asset",
 *     indexes={
 *      @ORM\Index(name="gallery", columns={"gallery_uuid", "type"})
 *     }
 * )
 */
class Asset implements Query\Viewable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="gallery_uuid", type="string", columnDefinition="CHAR(36) NOT NULL")
     */
    private $galleryUuid;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=8, nullable=false)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=255, nullable=false)
     */
    private $filename;

    /**
     * {@inheritdoc}
     */
    public function identifier()
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Asset
     */
    public function setId(int $id): Asset
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getGalleryUuid(): string
    {
        return $this->galleryUuid;
    }

    /**
     * @param string $galleryUuid
     *
     * @return Asset
     */
    public function setGalleryUuid(string $galleryUuid): Asset
    {
        $this->galleryUuid = $galleryUuid;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Asset
     */
    public function setType(string $type): Asset
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return string
     */
    public function getFilename(): string
    {
        return $this->filename;
    }

    /**
     * @param string $filename
     *
     * @return Asset
     */
    public function setFilename(string $filename): Asset
    {
        $this->filename = $filename;

        return $this;
    }
}
