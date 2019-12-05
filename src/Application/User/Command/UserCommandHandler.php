<?php

namespace App\Application\User\Command;

use App\Infrastructure\Persist\User\UserRepository;

abstract class UserCommandHandler implements \N3ttech\Messaging\Command\CommandHandling\CommandHandler
{
    /** @var UserRepository */
    protected $repository;

    /**
     * @param UserRepository $repository
     */
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
}
