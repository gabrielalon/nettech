<?php

namespace App\Infrastructure\Persist\Snapshot;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persist\Snapshot\DoctrineSnapshotStorageRepository")
 * @ORM\Table(
 *     name="snapshot_store",
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(name="aggregate", columns={"aggregate_id", "aggregate_type"})
 *     },
 *     indexes={
 *      @ORM\Index(name="version", columns={"last_version"}),
 *      @ORM\Index(name="created_at", columns={"created_at"})
 *     }
 *)
 */
class SnapshotStoreEntity
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
     * @ORM\Column(name="aggregate_id", type="guid")
     */
    private $aggregateId;

    /**
     * @ORM\Column(name="aggregate_type", type="string")
     */
    private $aggregateType;

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

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return SnapshotStoreEntity
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @param mixed $aggregateId
     * @return SnapshotStoreEntity
     */
    public function setAggregateId($aggregateId)
    {
        $this->aggregateId = $aggregateId;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAggregateType()
    {
        return $this->aggregateType;
    }

    /**
     * @param mixed $aggregateType
     * @return SnapshotStoreEntity
     */
    public function setAggregateType($aggregateType)
    {
        $this->aggregateType = $aggregateType;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAggregateObject()
    {
        return $this->aggregateObject;
    }

    /**
     * @param mixed $aggregateObject
     * @return SnapshotStoreEntity
     */
    public function setAggregateObject($aggregateObject)
    {
        $this->aggregateObject = $aggregateObject;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastVersion()
    {
        return $this->lastVersion;
    }

    /**
     * @param mixed $lastVersion
     * @return SnapshotStoreEntity
     */
    public function setLastVersion($lastVersion)
    {
        $this->lastVersion = $lastVersion;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     * @return SnapshotStoreEntity
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
