<?php

namespace App\Domain\Role\Command;

final class CreateRole
{
    private $id;
    private $name;
    private $permissions;

    public function __construct(string $id, string $name, array $permissions)
    {
        $this->id = $id;
        $this->name = $name;
        $this->permissions = $permissions;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPermissions(): array
    {
        return $this->permissions;
    }
}
