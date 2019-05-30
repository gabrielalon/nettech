<?php

namespace App\Domain\Country\ReadModel\Repository;

use App\Domain\Country\ReadModel\Entity\Country;

interface CountryRepositoryInterface
{
    public function save(Country $country): void;

    public function remove(Country $country): void;

    public function find(string $id): ?Country;

    public function findAll(): array;
}
