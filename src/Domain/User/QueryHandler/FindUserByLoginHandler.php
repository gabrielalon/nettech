<?php

namespace App\Domain\User\QueryHandler;

use App\Domain\User\Query\FindUserByLogin;
use App\Domain\User\ReadModel\Entity\User;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindUserByLoginHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(FindUserByLogin $query): ?User
    {
        return $this->repository->findOneBy(['login' => $query->getLogin()]);
    }
}
