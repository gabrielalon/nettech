<?php

namespace App\Domain\Country\Event;

use App\Domain\Country\ValueObject\CountryId;
use App\Domain\Country\ValueObject\Name;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

class Created implements DomainEventInterface
{
    /**
     * @var CountryId
     */
    private $countryId;

    /**
     * @var Name
     */
    private $name;

    public function __construct(CountryId $countryId, Name $name)
    {
        $this->countryId = $countryId;
        $this->name = $name;
    }

    public function getCountryId(): CountryId
    {
        return $this->countryId;
    }

    public function getName(): Name
    {
        return $this->name;
    }
}
