<?php

namespace App\Infrastructure\Persistence\Country\Repository;

use App\Domain\Country\ReadModel\Entity\Country;
use App\Domain\Country\ReadModel\Repository\CountryRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class CountryRepository implements CountryRepositoryInterface
{
    /**
     * @var EntityRepository
     */
    private $entityRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->entityRepository = new EntityRepository(
            $entityManager,
            $entityManager->getClassMetadata(Country::class)
        );
    }

    public function save(Country $country): void
    {
        $this->entityManager->persist($country);
        $this->entityManager->flush();
    }

    public function remove(Country $country): void
    {
        $this->entityManager->remove($country);
        $this->entityManager->flush();
    }

    public function find(string $id): ?Country
    {
        return $this->entityRepository->find($id);
    }

    public function findAll(): array
    {
        return $this->entityRepository->findAll();
    }
}
