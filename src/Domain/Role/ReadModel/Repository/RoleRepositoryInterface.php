<?php

namespace App\Domain\Role\ReadModel\Repository;

use App\Domain\Role\ReadModel\Entity\Role;

interface RoleRepositoryInterface
{
    public function save(Role $role): void;

    public function remove(Role $role): void;

    public function find(string $id): ?Role;

    public function findAll(): array;
}
