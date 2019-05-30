<?php

namespace App\Domain\Country\Entity;

use App\Domain\Country\Event\Created;
use App\Domain\Country\ValueObject\CountryId;
use App\Domain\Country\ValueObject\Name;
use App\Infrastructure\EventSourcing\Aggregate\AbstractAggregateRoot;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;

final class Country extends AbstractAggregateRoot
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
        $this->recordThat(new Created($countryId, $name));
    }

    public function getAggregateId(): IdentifyInterface
    {
        return $this->countryId;
    }

    public function getName(): Name
    {
        return $this->name;
    }

    protected function applyCreated(Created $event): void
    {
        $this->countryId = $event->getCountryId();
        $this->name = $event->getName();
    }
}
