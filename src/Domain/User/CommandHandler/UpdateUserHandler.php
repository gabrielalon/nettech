<?php

declare(strict_types=1);

namespace App\Domain\User\CommandHandler;

use App\Domain\User\Command\UpdateUserCommand;
use App\Domain\User\Entity\User;
use App\Domain\User\Service\PasswordEncoderService;
use App\Domain\User\Service\UserAsserter;
use App\Domain\User\ValueObject\FirstName;
use App\Domain\User\ValueObject\LastName;
use App\Domain\User\ValueObject\Login;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\EventSourcing\Aggregate\Persist\AggregateRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateUserHandler implements MessageHandlerInterface
{
    private $passwordEncoderService;
    private $aggregateRepository;

    /**
     * @var UserAsserter
     */
    private $userAsserter;

    public function __construct(
        PasswordEncoderService $passwordEncoderService,
        AggregateRepositoryInterface $aggregateRepository,
        UserAsserter $userAsserter
    ) {
        $this->passwordEncoderService = $passwordEncoderService;
        $this->aggregateRepository = $aggregateRepository;
        $this->userAsserter = $userAsserter;
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $this->userAsserter->assertUserExists(new UserId($command->getId()));

        /** @var User $user */
        $user = $this->aggregateRepository->find(
            new UserId($command->getId()),
            User::class
        );

        if (null !== $command->getFirstName()) {
            $user->changeFirstName(new FirstName($command->getFirstName()));
        }

        if (null !== $command->getLastName()) {
            $user->changeLastName(new LastName($command->getLastName()));
        }

        if (null !== $command->getLogin()) {
            $user->changeLogin(new Login($command->getLogin()));
        }

        if (null !== $command->getPassword()) {
            $user->changePassword(
                new Password($this->passwordEncoderService->encode($command->getPassword()))
            );
        }

        $this->aggregateRepository->save($user);
    }
}
