<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Snapshot\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\EventSourcing\Snapshot\Persist\DoctrineSnapshotStorageRepository")
 * @ORM\Table(name="snapshot_store")
 */
class SnapshotEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     */
    private $id;

    /**
     * @ORM\Column(name="aggregate_id", type="guid")
     */
    private $aggregateId;

    /**
     * @ORM\Column(name="aggregate_class", type="string")
     */
    private $aggregateClass;

    /**
     * @ORM\Column(name="aggregate_object", type="text")
     */
    private $aggregateObject;

    /**
     * @ORM\Column(name="last_version", type="integer")
     */
    private $lastVersion;

    /**
     * @ORM\Column(name="created_at", type="datetime_immutable")
     */
    private $createdAt;

    public function getId()
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    public function setAggregateId($aggregateId): void
    {
        $this->aggregateId = $aggregateId;
    }

    public function getAggregateClass()
    {
        return $this->aggregateClass;
    }

    public function setAggregateClass(string $aggregateClass): void
    {
        $this->aggregateClass = $aggregateClass;
    }

    public function getAggregateObject()
    {
        return $this->aggregateObject;
    }

    public function setAggregateObject(string $aggregateObject): void
    {
        $this->aggregateObject = $aggregateObject;
    }

    public function getLastVersion()
    {
        return $this->lastVersion;
    }

    public function setLastVersion(int $lastVersion): void
    {
        $this->lastVersion = $lastVersion;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
