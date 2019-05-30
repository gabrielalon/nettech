<?php

namespace App\Domain\User\QueryHandler;

use App\Domain\User\Query\FindUser;
use App\Domain\User\ReadModel\Entity\User;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindUserHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindUser $query): ?User
    {
        return $this->repository->find($query->getUserId());
    }
}
