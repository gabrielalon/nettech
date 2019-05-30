<?php

namespace App\Domain\Role\CommandHandler;

use App\Domain\Role\Command\AssignPermission;
use App\Domain\Role\Entity\Role;
use App\Domain\Role\Service\PermissionService;
use App\Domain\Role\ValueObject\RoleId;
use App\Infrastructure\EventSourcing\Aggregate\Persist\AggregateRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AssignPermissionHandler implements MessageHandlerInterface
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

    public function __invoke(AssignPermission $command)
    {
        /** @var Role $entity */
        $entity = $this->aggregateRepository->find(new RoleId($command->getId()), Role::class);

        $entity->assignPermission(
            $this->permissionService->getPermission(
            $command->getPermissionSubject(),
            $command->getPermissionAction()
        )
        );

        $this->aggregateRepository->save($entity);
    }
}
