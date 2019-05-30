<?php

declare(strict_types=1);

namespace App\Domain\User\Command;

final class CreateUser
{
    private $id;
    private $firstName;
    private $lastName;
    private $login;
    private $password;
    private $roleId;

    public function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $login,
        string $password,
        ?string $roleId
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->login = $login;
        $this->password = $password;
        $this->roleId = $roleId;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getRoleId(): ?string
    {
        return $this->roleId;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getPassword(): string
    {
        return $this->password;
    }
}
