<?php

namespace App\Domain\Country\QueryHandler;

use App\Domain\Country\Query\FindCountries;
use App\Domain\Country\ReadModel\Repository\CountryRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindCountriesHandler implements MessageHandlerInterface
{
    private $repository;

    public function __construct(CountryRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindCountries $query)
    {
        return $this->repository->findAll();
    }
}
