<?php

declare(strict_types=1);

namespace App\Domain\User\CommandHandler;

use App\Domain\User\Command\CreateUser;
use App\Domain\User\Entity\User;
use App\Domain\User\Service\PasswordEncoderService;
use App\Domain\User\ValueObject\FirstName;
use App\Domain\User\ValueObject\LastName;
use App\Domain\User\ValueObject\Login;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\EventSourcing\Aggregate\Persist\AggregateRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateUserHandler implements MessageHandlerInterface
{
    private $passwordEncoderService;
    private $aggregateRepository;

    public function __construct(
        PasswordEncoderService $passwordEncoderService,
        AggregateRepositoryInterface $aggregateRepository
    ) {
        $this->passwordEncoderService = $passwordEncoderService;
        $this->aggregateRepository = $aggregateRepository;
    }

    public function __invoke(CreateUser $command): void
    {
        $user = new User(
            new UserId($command->getId()),
            new FirstName($command->getFirstName()),
            new LastName($command->getLastName()),
            new Login($command->getLogin()),
            new Password($this->passwordEncoderService->encode($command->getPassword()))
        );

        $this->aggregateRepository->save($user);
    }
}
