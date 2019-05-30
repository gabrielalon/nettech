<?php

namespace App\Domain\User\ReadModel\Repository;

use App\Domain\User\ReadModel\Entity\User;
use Doctrine\Common\Collections\Criteria;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    public function remove(User $user): void;

    public function find(string $userId): ?User;

    public function findById(string $id): ?User;

    public function findAll(): array;

    public function findBy(Criteria $criteria): array;
}
