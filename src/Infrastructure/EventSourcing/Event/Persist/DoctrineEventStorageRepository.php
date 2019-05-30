<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Event\Persist;

use App\Infrastructure\EventSourcing\Event\DomainMessage;
use App\Infrastructure\EventSourcing\Event\DomainMessagesStream;
use App\Infrastructure\EventSourcing\Event\Entity\EventStoreEntity;
use App\Infrastructure\IdentityGenerator\IdentityGeneratorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Serializer\SerializerInterface;

// TODO: Clean whole ES Framework

final class DoctrineEventStorageRepository extends ServiceEntityRepository implements EventStorageRepositoryInterface
{
    private $serializer;
    private $identityGenerator;

    public function __construct(
        ManagerRegistry $registry,
        SerializerInterface $serializer,
        IdentityGeneratorInterface $identityGenerator
    ) {
        parent::__construct($registry, EventStoreEntity::class);
        $this->serializer = $serializer;
        $this->identityGenerator = $identityGenerator;
    }

    public function loadStream(string $aggregateId, int $playHead): DomainMessagesStream
    {
        $criteria = new Criteria();
        $criteria->where($criteria::expr()->eq('aggregateRootId', $aggregateId));
        $criteria->andWhere($criteria::expr()->gt('playHead', $playHead));
        $criteria->orderBy(['playHead' => 'ASC']);
        $rawEvents = $this->matching($criteria);

        $domainEvents = [];
        /** @var EventStoreEntity $rawEvent */
        foreach ($rawEvents as $rawEvent) {
            $domainEvents[] = new DomainMessage(
                $rawEvent->getAggregateRootId(),
                $rawEvent->getPlayHead(),
                unserialize($rawEvent->getEventObject()),
                $rawEvent->getOccurredOn()
            );
        }

        return new DomainMessagesStream($domainEvents);
    }

    public function saveStream(DomainMessagesStream $events): void
    {
        foreach ($events as $event) {
            $this->save($event);
        }
    }

    public function save(DomainMessage $domainMessage): void
    {
        $eventStoreEntity = new EventStoreEntity(
            $this->identityGenerator->generate(),
            $domainMessage->getId(),
            $domainMessage->getType(),
            serialize($domainMessage->getPayload()),
            $domainMessage->getPlayHead(),
            $domainMessage->getRecordedOn()
        );

        $this->_em->persist($eventStoreEntity);
        $this->_em->flush();
    }
}
