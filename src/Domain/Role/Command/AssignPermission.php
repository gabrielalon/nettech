<?php

namespace App\Domain\Role\Command;

final class AssignPermission
{
    private $id;
    private $permissionSubject;
    private $permissionAction;

    public function __construct(
        string $id,
        string $permissionSubject,
        string $permissionAction
    ) {
        $this->id = $id;
        $this->permissionSubject = $permissionSubject;
        $this->permissionAction = $permissionAction;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPermissionSubject(): string
    {
        return $this->permissionSubject;
    }

    public function getPermissionAction(): string
    {
        return $this->permissionAction;
    }
}
