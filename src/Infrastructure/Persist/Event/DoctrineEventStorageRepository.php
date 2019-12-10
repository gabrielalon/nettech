<?php

namespace App\Infrastructure\Persist\Event;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use N3ttech\Messaging\Event\Event\Event;
use N3ttech\Messaging\Event\EventStore\Serializer;
use N3ttech\Messaging\Event\EventStore\Stream;
use N3ttech\Messaging\Event\Persist\EventStreamRepository;
use N3ttech\Valuing\Identity\AggregateId;

final class DoctrineEventStorageRepository extends ServiceEntityRepository implements EventStreamRepository
{
    /** @var Serializer */
    private $serializer;

    /**
     * DoctrineEventStorageRepository constructor.
     *
     * @param Serializer      $serializer
     * @param ManagerRegistry $registry
     */
    public function __construct(
        Serializer $serializer,
        ManagerRegistry $registry
    ) {
        $this->serializer = $serializer;
        parent::__construct($registry, EventStoreEntity::class);
    }

    /**
     * {@inheritdoc}
     */
    public function save(Event $event): void
    {
        $eventStoreEntity = new EventStoreEntity();
        $eventStoreEntity->setEventId($event->aggregateId());
        $eventStoreEntity->setEventName($event->eventName());
        $eventStoreEntity->setMetadata($this->serializer->encode($event->metadata()));
        $eventStoreEntity->setPayload($this->serializer->encode($event->payload()));
        $eventStoreEntity->setVersion($event->version());
        $eventStoreEntity->setOccurredOn(\DateTimeImmutable::createFromMutable($event->recordedOn()));

        try {
            $this->_em->persist($eventStoreEntity);
            $this->_em->flush();
        } catch (\Exception $e) {
            var_dump($e->__toString());
            die;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(AggregateId $aggregateId, int $lastVersion): Stream\EventStreamCollection
    {
        $criteria = new Criteria();
        $criteria->where($criteria::expr()->eq('eventId', $aggregateId->toString()));
        $criteria->andWhere($criteria::expr()->gt('version', $lastVersion));
        $criteria->orderBy(['version' => 'ASC']);

        $collection = new Stream\EventStreamCollection();

        /** @var EventStoreEntity $event */
        foreach ($this->matching($criteria) as $event) {
            $collection->add(new Stream\EventStream(
                $event->getEventId(),
                $event->getVersion(),
                $event->getEventName(),
                $this->serializer->decode($event->getPayload()),
                $this->serializer->decode($event->getMetadata())
            ));
        }

        return $collection;
    }
}
