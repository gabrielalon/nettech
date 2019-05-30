<?php

namespace App\Infrastructure\Persistence\User\Repository;

use App\Domain\User\ReadModel\Entity\User;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserRepository implements UserRepositoryInterface, UserLoaderInterface
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
            $entityManager->getClassMetadata(User::class)
        );
    }

    public function save(User $role): void
    {
        $this->entityManager->persist($role);
        $this->entityManager->flush();
    }

    public function remove(User $role): void
    {
        $this->entityManager->remove($role);
        $this->entityManager->flush();
    }

    public function find(string $userId): ?User
    {
        return $this->entityRepository->find($userId);
    }

    public function findAll(): array
    {
        return $this->entityRepository->findAll();
    }

    public function findById(string $id): ?User
    {
        return $this->entityRepository->find($id); // TODO: Remove it, leaved for now due to Symfony authorization
    }

    public function findBy(Criteria $criteria): array
    {
        return $this->entityRepository->matching($criteria)->toArray();
    }

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     */
    public function loadUserByUsername($username)
    {
        return $this->entityRepository->findOneBy(['login' => $username]);
    }
}
