<?php

namespace App\Application\Gallery\Query\ReadModel\Entity;

use Doctrine\ORM\Mapping as ORM;
use N3ttech\Messaging\Query\Query;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persist\Gallery\DoctrineGalleryRepository")
 * @ORM\Table(
 *     name="gallery",
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(name="name", columns={"name"})},
 *     indexes={
 *      @ORM\Index(name="source", columns={"source"})
 *     }
 * )
 */
class Gallery implements Query\Viewable
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, columnDefinition="CHAR(36) NOT NULL")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $uuid;

    /**
     * @var string
     *
     * @ORM\Column(name="source", type="string", length=128, nullable=false)
     */
    private $source;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="datetimetz", name="created_at")
     */
    private $created_at;

    /**
     * @var int
     */
    private $asset_counter;

    /**
     * {@inheritdoc}
     */
    public function identifier()
    {
        return $this->getUuid();
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return \DateTime
     */
    public function creationDate(): \DateTime
    {
        return date_timestamp_set(date_create(), strtotime($this->getCreatedAt()));
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }

    /**
     * @return int
     */
    public function assetCounter(): int
    {
        return $this->asset_counter;
    }

    /**
     * @param string $uuid
     */
    public function setUuid(string $uuid): void
    {
        $this->uuid = $uuid;
    }

    /**
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->source = $source;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @param string $created_at
     */
    public function setCreatedAt(string $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @param int $asset_counter
     */
    public function setAssetCounter(int $asset_counter): void
    {
        $this->asset_counter = $asset_counter;
    }
}
