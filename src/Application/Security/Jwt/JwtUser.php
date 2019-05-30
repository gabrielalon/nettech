<?php

namespace App\Application\Security\Jwt;

use App\Domain\User\ReadModel\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;

class JwtUser implements JwtUserInterface
{
    private $tokenStorage;

    public function __construct(
        TokenStorageInterface $tokenStorage
    ) {
        $this->tokenStorage = $tokenStorage;
    }

    public function get(): User
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user instanceof User) {
            throw new TokenNotFoundException('User not found.');
        }

        return $user;
    }
}
