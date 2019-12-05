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
     * @var \Ramsey\Uuid\UuidInterface
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
     * @inheritDoc
     */
    public function identifier()
    {
        return $this->uuid->toString();
    }

    /**
     * @return \Ramsey\Uuid\UuidInterface
     */
    public function getUuid(): \Ramsey\Uuid\UuidInterface
    {
        return $this->uuid;
    }

    /**
     * @param \Ramsey\Uuid\UuidInterface $uuid
     * @return Gallery
     */
    public function setUuid(\Ramsey\Uuid\UuidInterface $uuid): Gallery
    {
        $this->uuid = $uuid;
        return $this;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return Gallery
     */
    public function setSource(string $source): Gallery
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Gallery
     */
    public function setName(string $name): Gallery
    {
        $this->name = $name;
        return $this;
    }
}
