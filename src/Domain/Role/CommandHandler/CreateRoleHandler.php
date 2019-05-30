<?php

namespace App\Domain\Role\CommandHandler;

use App\Domain\Role\Collection\PermissionCollection;
use App\Domain\Role\Command\CreateRole;
use App\Domain\Role\Entity\Role;
use App\Domain\Role\ValueObject\Name;
use App\Domain\Role\ValueObject\RoleId;
use App\Infrastructure\EventSourcing\Aggregate\Persist\AggregateRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateRoleHandler implements MessageHandlerInterface
{
    /**
     * @var AggregateRepositoryInterface
     */
    private $aggregateRepository;

    public function __construct(AggregateRepositoryInterface $aggregateRepository)
    {
        $this->aggregateRepository = $aggregateRepository;
    }

    public function __invoke(CreateRole $command)
    {
        $entity = new Role(
            new RoleId($command->getId()),
            new Name($command->getName()),
            new PermissionCollection($command->getPermissions())
        );

        $this->aggregateRepository->save($entity);
    }
}
