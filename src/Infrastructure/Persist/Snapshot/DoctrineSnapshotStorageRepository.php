<?php

namespace App\Infrastructure\Persist\Snapshot;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use N3ttech\Messaging\Aggregate;
use N3ttech\Messaging\Snapshot\Persist\SnapshotRepository;
use N3ttech\Messaging\Snapshot\Snapshot;
use N3ttech\Messaging\Snapshot\SnapshotStore\Serializer;
use N3ttech\Valuing\Identity\AggregateId;

final class DoctrineSnapshotStorageRepository extends ServiceEntityRepository implements SnapshotRepository
{
    use Aggregate\AggregateTranslatorTrait;

    /** @var Serializer */
    private $serializer;

    /**
     * DoctrineSnapshotStorageRepository constructor.
     * @param Serializer $serializer
     * @param ManagerRegistry $registry
     */
    public function __construct(
        Serializer $serializer,
        ManagerRegistry $registry
    ) {
        $this->serializer = $serializer;
        parent::__construct($registry, SnapshotStoreEntity::class);
    }

    /**
     * @inheritDoc
     */
    public function save(Snapshot\Snapshot $snapshot): void
    {
        /** @var Aggregate\AggregateRoot $aggregateRoot */
        $aggregateRoot = $snapshot->getAggregateRoot();
        $aggregateType = Aggregate\AggregateType::fromAggregateRoot($aggregateRoot);
        $aggregateId = $this->getAggregateTranslator()->extractAggregateId($aggregateRoot);

        $entity = $this->findOneBy(['aggregateId' => $aggregateId]);

        if (null === $entity) {
            $entity = new SnapshotStoreEntity();
            $entity->setCreatedAt(\DateTimeImmutable::createFromMutable(new \DateTime('now')));
        }

        $entity->setAggregateId($aggregateId);
        $entity->setAggregateType($aggregateType->getAggregateType());
        $entity->setAggregateObject($this->serializer->serialize($aggregateRoot));
        $entity->setLastVersion($snapshot->getLastVersion());

        $this->_em->persist($entity);
        $this->_em->flush();
    }

    /**
     * @inheritDoc
     */
    public function get(Aggregate\AggregateType $aggregateType, AggregateId $aggregateId): Snapshot\Snapshot
    {
        /** @var SnapshotStoreEntity $entity */
        $entity = $this->findOneBy(['aggregateId' => $aggregateId->toString(), 'aggregateType' => $aggregateType]);

        if (null === $aggregateType) {
            $aggregateRoot = Aggregate\EventBridge\AggregateRootDecorator::newInstance();
            $aggregateRoot->setAggregateId($aggregateId);

            return new Snapshot\Snapshot(
                $aggregateRoot,
                0,
                new \DateTimeImmutable('@' . time())
            );
        }

        return new Snapshot\Snapshot(
            $this->serializer->unserialize($entity->getAggregateObject()),
            $entity->getLastVersion(),
            $entity->getCreatedAt()
        );
    }
}
