<?php

namespace App\Domain\Role\CommandHandler;

use App\Domain\Role\Command\RemoveRole;
use App\Domain\Role\Entity\Role;
use App\Domain\Role\ValueObject\RoleId;
use App\Infrastructure\EventSourcing\Aggregate\Persist\AggregateRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class RemoveRoleHandler implements MessageHandlerInterface
{
    /**
     * @var AggregateRepositoryInterface
     */
    private $aggregateRepository;

    public function __construct(AggregateRepositoryInterface $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    public function __invoke(RemoveRole $command)
    {
        /** @var Role $entity */
        $entity = $this->aggregateRepository->find(new RoleId($command->getId()), Role::class);
        $entity->remove();

        $this->aggregateRepository->save($entity);
    }
}
