<?php

namespace App\Domain\Country\CommandHandler;

use App\Domain\Country\Command\Create;
use App\Domain\Country\Entity\Country;
use App\Domain\Country\ValueObject\CountryId;
use App\Domain\Country\ValueObject\Name;
use App\Infrastructure\EventSourcing\Aggregate\Persist\AggregateRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateHandler implements MessageHandlerInterface
{
    /**
     * @var AggregateRepositoryInterface
     */
    private $aggregateRepository;

    public function __construct(AggregateRepositoryInterface $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    public function __invoke(Create $command)
    {
        $this->aggregateRepository->save(
            new Country(
            new CountryId($command->getCountryId()),
            new Name($command->getName())
        )
        );
    }
}
