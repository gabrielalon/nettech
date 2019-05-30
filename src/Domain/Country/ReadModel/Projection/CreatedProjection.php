<?php

namespace App\Domain\Country\ReadModel\Projection;

use App\Domain\Country\Event\Created;
use App\Domain\Country\ReadModel\Entity\Country;
use App\Domain\Country\ReadModel\Repository\CountryRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreatedProjection implements MessageHandlerInterface
{
    /**
     * @var CountryRepositoryInterface
     */
    private $countryRepository;

    public function __construct(CountryRepositoryInterface $countryRepository)
    {
        $this->countryRepository = $countryRepository;
    }

    public function __invoke(Created $event): void
    {
        $this->countryRepository->save(
            new Country(
            $event->getCountryId()->getValue(),
            $event->getName()->getValue()
        )
        );
    }
}
