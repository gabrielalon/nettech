<?php

namespace App\Domain\User\QueryHandler;

use App\Domain\User\Query\GetUsersQuery;
use App\Domain\User\ReadModel\Repository\UserRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetUsersHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepositoryInterface
     */
    private $repository;

    public function __construct(UserRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GetUsersQuery $query)
    {
        return [['title' => 'TestLogin']];
    }
}
