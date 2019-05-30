<?php

namespace App\Domain\Role\CommandHandler;

use App\Domain\Role\Command\RevokePermission;
use App\Domain\Role\Entity\Role;
use App\Domain\Role\Service\PermissionService;
use App\Domain\Role\ValueObject\RoleId;
use App\Infrastructure\EventSourcing\Aggregate\Persist\AggregateRepositoryInterface;

class RevokePermissionHandler
{
    /**
     * @var AggregateRepositoryInterface
     */
    private $aggregateRepository;

    /**
     * @var PermissionService
     */
    private $permissionService;

    public function __construct(
        AggregateRepositoryInterface $aggregateRepository,
        PermissionService $permissionService
    ) {
        $this->aggregateRepository = $aggregateRepository;
        $this->permissionService = $permissionService;
    }

    public function __invoke(RevokePermission $command)
    {
        /** @var Role $entity */
        $entity = $this->aggregateRepository->find(new RoleId($command->getId()), Role::class);

        $entity->revokePermission(
            $this->permissionService->getPermission($command->getSubject(), $command->getAction())
        );

        $this->aggregateRepository->save($entity);
    }
}
