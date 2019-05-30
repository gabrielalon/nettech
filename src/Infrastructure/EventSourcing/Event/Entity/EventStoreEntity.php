<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Event\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\EventSourcing\Event\Persist\DoctrineEventStorageRepository")
 * @ORM\Table(name="event_store", indexes={
 *     @Index(name="id", columns={"id"}),
 *     @Index(name="event_store_search_index", columns={"aggregate_root_id", "occurred_on"})
 * })
 */
class EventStoreEntity
{
    /**
     * @ORM\Id()
     * @ORM\Column(name="id", type="guid")
     */
    protected $id;

    /**
     * @ORM\Column(name="aggregate_root_id", type="guid")
     */
    protected $aggregateRootId;

    /**
     * @ORM\Column(name="event_class", type="string")
     */
    protected $eventClass;

    /**
     * @ORM\Column(name="event_object", type="text")
     */
    protected $eventObject;

    /**
     * @ORM\Column(name="playhead", type="integer")
     */
    protected $playHead;

    /**
     * @ORM\Column(name="occurred_on", type="datetime_immutable")
     */
    protected $occurredOn;

    public function __construct(
        string $id,
        string $aggregateRootId,
        string $eventClass,
        string $eventObject,
        int $playHead,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->aggregateRootId = $aggregateRootId;
        $this->eventClass = $eventClass;
        $this->eventObject = $eventObject;
        $this->playHead = $playHead;
        $this->occurredOn = $createdAt;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getAggregateRootId(): string
    {
        return $this->aggregateRootId;
    }

    public function getEventClass(): string
    {
        return $this->eventClass;
    }

    public function getEventObject(): string
    {
        return $this->eventObject;
    }

    public function getPlayHead(): int
    {
        return $this->playHead;
    }

    public function getOccurredOn(): \DateTimeImmutable
    {
        return $this->occurredOn;
    }
}
