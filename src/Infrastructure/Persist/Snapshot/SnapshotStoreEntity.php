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
     * @var string|int
     *
     * @ORM\Column(name="aggregate_id", type="guid")
     */
    private $aggregateId;

    /**
     * @var string
     *
     * @ORM\Column(name="aggregate_type", type="string")
     */
    private $aggregateType;

    /**
     * @var string
     *
     * @ORM\Column(name="aggregate_object", type="text")
     */
    private $aggregateObject;

    /**
     * @var int
     *
     * @ORM\Column(name="last_version", type="integer")
     */
    private $lastVersion;

    /**
     * @var \DateTimeImmutable
     *
     * @ORM\Column(name="created_at", type="datetime_immutable")
     */
    private $createdAt;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return SnapshotStoreEntity
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string|int
     */
    public function getAggregateId()
    {
        return $this->aggregateId;
    }

    /**
     * @param string|int $aggregateId
     *
     * @return SnapshotStoreEntity
     */
    public function setAggregateId($aggregateId)
    {
        $this->aggregateId = $aggregateId;

        return $this;
    }

    /**
     * @return string
     */
    public function getAggregateType()
    {
        return $this->aggregateType;
    }

    /**
     * @param string $aggregateType
     *
     * @return SnapshotStoreEntity
     */
    public function setAggregateType($aggregateType)
    {
        $this->aggregateType = $aggregateType;

        return $this;
    }

    /**
     * @return string
     */
    public function getAggregateObject()
    {
        return $this->aggregateObject;
    }

    /**
     * @param string $aggregateObject
     *
     * @return SnapshotStoreEntity
     */
    public function setAggregateObject($aggregateObject)
    {
        $this->aggregateObject = $aggregateObject;

        return $this;
    }

    /**
     * @return int
     */
    public function getLastVersion()
    {
        return $this->lastVersion;
    }

    /**
     * @param int $lastVersion
     *
     * @return SnapshotStoreEntity
     */
    public function setLastVersion($lastVersion)
    {
        $this->lastVersion = $lastVersion;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function creationDate(): \DateTime
    {
        return date_timestamp_set(date_create(), $this->createdAt->getTimestamp());
    }

    /**
     * @param \DateTimeImmutable $createdAt
     *
     * @return SnapshotStoreEntity
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
