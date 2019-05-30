<?php

namespace App\Domain\Role\QueryHandler;

use App\Domain\Role\Query\FindRole;
use App\Domain\Role\ReadModel\Repository\RoleRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindRoleHandler implements MessageHandlerInterface
{
    /**
     * @var RoleRepositoryInterface
     */
    private $repository;

    public function __construct(RoleRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindRole $query)
    {
        return $this->repository->find($query->getId());
    }
}
