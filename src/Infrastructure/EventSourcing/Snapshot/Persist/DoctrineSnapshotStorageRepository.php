<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Snapshot\Persist;

use App\Infrastructure\EventSourcing\Aggregate\AggregateRootInterface;
use App\Infrastructure\EventSourcing\Aggregate\AggregateType;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;
use App\Infrastructure\EventSourcing\Snapshot\Entity\SnapshotEntity;
use App\Infrastructure\EventSourcing\Snapshot\Factory\SnapshotFactory;
use App\Infrastructure\EventSourcing\Snapshot\Persist\Exception\SnapshotNotFoundException;
use App\Infrastructure\EventSourcing\Snapshot\Snapshot;
use App\Infrastructure\IdentityGenerator\IdentityGeneratorInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

final class DoctrineSnapshotStorageRepository extends ServiceEntityRepository implements SnapshotStorageRepositoryInterface
{
    /**
     * @var IdentityGeneratorInterface
     */
    private $identityGenerator;

    public function __construct(
        ManagerRegistry $registry,
        IdentityGeneratorInterface $identityGenerator
    ) {
        parent::__construct($registry, SnapshotEntity::class);

        $this->identityGenerator = $identityGenerator;
    }

    public function save(Snapshot $snapshot): void
    {
        $entity = $this->findOneBy(['aggregateId' => $snapshot->getAggregateId()]);

        if (null === $entity) {
            $entity = new SnapshotEntity();
            $entity->setId($this->identityGenerator->generate());
        }

        $entity->setAggregateId($snapshot->getAggregateId());
        $entity->setAggregateClass($snapshot->getAggregateType()->__toString());
        $entity->setAggregateObject(serialize($snapshot->getAggregateRoot()));
        $entity->setLastVersion($snapshot->getPlayHead());
        $entity->setCreatedAt(new \DateTimeImmutable('@' . time()));

        $this->_em->persist($entity);
        $this->_em->flush();
    }

    public function get(AggregateType $aggregateType, IdentifyInterface $aggregateId): Snapshot
    {
        /** @var SnapshotEntity $snapshotEntity */
        $snapshotEntity = $this->findOneBy(
            ['aggregateClass' => $aggregateType->__toString(), 'aggregateId' => $aggregateId->__toString()]
        );

        if (null === $snapshotEntity) {
            throw new SnapshotNotFoundException(
                sprintf(
                'Snapshot %s with id %s was not found',
                $aggregateType->__toString(),
                $aggregateId->__toString()
                )
            );
        }

        /** @var AggregateRootInterface $aggregateRoot */
        $aggregateRoot = unserialize($snapshotEntity->getAggregateObject());

        return SnapshotFactory::createFromAggregate($aggregateRoot);
    }
}
