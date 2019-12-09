<?php

namespace App\Application\User\Service;

use App\Application\User\Query\ReadModel\Entity\User;
use App\Application\User\Query\ReadModel\Entity\UserCollection;
use App\Infrastructure\IdentityGenerator\IdentityGeneratorInterface;
use N3ttech\Messaging\Query\Exception;

class UserService
{
    /** @var UserQueryManager */
    private $query;

    /** @var UserCommandManager */
    private $command;

    /** @var IdentityGeneratorInterface */
    private $identityGenerator;

    /**
     * UserService constructor.
     * @param UserQueryManager $query
     * @param UserCommandManager $command
     * @param IdentityGeneratorInterface $identityGenerator
     */
    public function __construct(
        UserQueryManager $query,
        UserCommandManager $command,
        IdentityGeneratorInterface $identityGenerator
    ) {
        $this->query = $query;
        $this->command = $command;
        $this->identityGenerator = $identityGenerator;
    }

    /**
     * @param string $login
     * @param string $password
     * @return User
     * @throws \Exception
     */
    public function createUser(string $login, string $password): User
    {
        try {
            return $this->findUser($login);
        } catch (Exception\ResourceNotFoundException $e) {
            $uuid = $this->identityGenerator->generate();
            $this->command->create($uuid, $login, $password);
        }

        return $this->findUser($login);
    }

    /**
     * @param string $uuid
     */
    public function removeUser(string $uuid): void
    {
        $this->command->remove($uuid);
    }

    /**
     * @param string $login
     * @return User
     * @throws Exception\ResourceNotFoundException
     */
    public function findUser(string $login): User
    {
        return $this->query->findOneByLogin($login);
    }

    /**
     * @return UserCollection
     */
    public function findAllUsers(): UserCollection
    {
        return $this->query->findAll();
    }
}