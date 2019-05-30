<?php

namespace App\Tests\Domain\Country\Entity;

use App\Domain\Country\Entity\Country;
use App\Domain\Country\Event\Created;
use App\Domain\Country\ValueObject\CountryId;
use App\Domain\Country\ValueObject\Name;
use App\Tests\Infrastructure\EventSourcing\Aggregate\AggregateRootScenarioTestCase;
use Ramsey\Uuid\Uuid;

class CountryTest extends AggregateRootScenarioTestCase
{
    protected function getAggregateRootClass(): string
    {
        return Country::class;
    }

    public function testCreate()
    {
        $countryId = new CountryId(Uuid::uuid4());
        $countryName = new Name('Polska');

        $this->scenario
            ->when(function () use ($countryId, $countryName) {
                return new Country(
                    $countryId,
                    $countryName
                );
            })
            ->then([
                new Created(
                    $countryId,
                    $countryName
                ),
            ])
            ->thenCallable(function (Country $country) use ($countryId, $countryName) {
                $this->assertSame($countryId, $country->getAggregateId());
                $this->assertSame($countryName, $country->getName());
            });
    }
}
