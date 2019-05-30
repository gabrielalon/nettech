<?php

declare(strict_types=1);

namespace App\Domain\User\Symfony\Bridge;

use Symfony\Component\Security\Core\User\UserInterface;

class PasswordEncoderUserStub implements UserInterface
{
    /**
     * @var string
     */
    private $password;

    public function __construct(string $password)
    {
        $this->password = $password;
    }

    public function getRoles(): array
    {
        throw new \LogicException('PasswordEncoderUser can not return roles');
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        throw new \LogicException('PasswordEncoderUser can not return user name');
    }

    public function eraseCredentials(): void
    {
        throw new \LogicException('PasswordEncoderUser can not erase credentials');
    }
}
