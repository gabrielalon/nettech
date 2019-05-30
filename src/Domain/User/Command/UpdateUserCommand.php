<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

final class UpdateUserCommand
{
    private $id;
    private $firstName;
    private $lastName;
    private $login;
    private $password;

    public function __construct(string $id, ?string $firstName, ?string $lastName, ?string $login, ?string $password)
    {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->login = $login;
        $this->password = $password;
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }
}
