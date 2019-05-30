<?php

declare(strict_types=1);

namespace App\Domain\User\CommandHandler;

use App\Domain\User\Command\RemoveUserCommand;
use App\Domain\User\Entity\User;
use App\Domain\User\Service\UserAsserter;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\EventSourcing\Aggregate\Persist\AggregateRepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class RemoveUserHandler implements MessageHandlerInterface
{
    private $aggregateRepository;
    private $logger;

    /**
     * @var UserAsserter
     */
    private $userAsserter;

    public function __construct(
        AggregateRepositoryInterface $aggregateRepository,
        LoggerInterface $logger,
        UserAsserter $userAsserter
    ) {
        $this->aggregateRepository = $aggregateRepository;
        $this->logger = $logger;
        $this->userAsserter = $userAsserter;
    }

    public function __invoke(RemoveUserCommand $command)
    {
        $this->userAsserter->assertUserExists(new UserId($command->getId()));

        /** @var User $user */
        $user = $this->aggregateRepository->find(
            new UserId($command->getId()),
            User::class
        );

        $user->remove();
        $this->aggregateRepository->save($user);
    }
}
