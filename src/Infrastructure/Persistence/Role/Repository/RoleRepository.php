<?php

namespace App\Infrastructure\Persistence\Role\Repository;

use App\Domain\Role\ReadModel\Entity\Role;
use App\Domain\Role\ReadModel\Repository\RoleRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

final class RoleRepository implements RoleRepositoryInterface
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
            $entityManager->getClassMetadata(Role::class)
        );
    }

    public function save(Role $role): void
    {
        $this->entityManager->persist($role);
        $this->entityManager->flush();
    }

    public function remove(Role $role): void
    {
        $this->entityManager->remove($role);
        $this->entityManager->flush();
    }

    public function find(string $id): ?Role
    {
        return $this->entityRepository->find($id);
    }

    public function findAll(): array
    {
        return $this->entityRepository->findAll();
    }
}
