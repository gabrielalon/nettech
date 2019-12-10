<?php

namespace App\Infrastructure\Persist\Event;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persist\Event\DoctrineEventStorageRepository")
 * @ORM\Table(
 *     name="event_store",
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(name="event", columns={"event_id", "version"})
 *     },
 *     indexes={
 *      @ORM\Index(name="event_name", columns={"event_name"}),
 *      @ORM\Index(name="occurred_on", columns={"occurred_on"})
 *     }
 *)
 */
class EventStoreEntity
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
     * @ORM\Column(name="event_id", type="guid")
     */
    private $eventId;

    /**
     * @ORM\Column(name="event_name", type="string")
     */
    private $eventName;

    /**
     * @ORM\Column(name="version", type="integer")
     */
    private $version;

    /**
     * @ORM\Column(name="payload", type="text")
     */
    private $payload;

    /**
     * @ORM\Column(name="metadata", type="text")
     */
    private $metadata;

    /**
     * @ORM\Column(name="occurred_on", type="datetime_immutable")
     */
    private $occurredOn;

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
     * @return EventStoreEntity
     */
    public function setId(int $id): EventStoreEntity
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEventId()
    {
        return $this->eventId;
    }

    /**
     * @param mixed $eventId
     *
     * @return EventStoreEntity
     */
    public function setEventId($eventId)
    {
        $this->eventId = $eventId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getEventName()
    {
        return $this->eventName;
    }

    /**
     * @param mixed $eventName
     *
     * @return EventStoreEntity
     */
    public function setEventName($eventName)
    {
        $this->eventName = $eventName;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param mixed $version
     *
     * @return EventStoreEntity
     */
    public function setVersion($version)
    {
        $this->version = $version;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     *
     * @return EventStoreEntity
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMetadata()
    {
        return $this->metadata;
    }

    /**
     * @param mixed $metadata
     *
     * @return EventStoreEntity
     */
    public function setMetadata($metadata)
    {
        $this->metadata = $metadata;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOccurredOn()
    {
        return $this->occurredOn;
    }

    /**
     * @param mixed $occurredOn
     *
     * @return EventStoreEntity
     */
    public function setOccurredOn($occurredOn)
    {
        $this->occurredOn = $occurredOn;

        return $this;
    }
}
