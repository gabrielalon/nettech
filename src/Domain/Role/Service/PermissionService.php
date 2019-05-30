<?php

namespace App\Domain\Role\Service;

use App\Domain\Role\ValueObject\Permission;
use App\Infrastructure\Permission\Loader\Exception\ActionNotFoundException;
use App\Infrastructure\Permission\Loader\Exception\SubjectNotFoundException;
use App\Infrastructure\Permission\Loader\LoaderInterface;

class PermissionService
{
    /**
     * @var LoaderInterface
     */
    private $loader;

    public function __construct(LoaderInterface $loader)
    {
        $this->loader = $loader;
    }

    /**
     * @throws ActionNotFoundException
     * @throws SubjectNotFoundException
     */
    public function getPermission(string $subject, string $action): Permission
    {
        $rawPermission = $this->loader->get($subject, $action);

        return new Permission(
            $rawPermission['action'],
            $rawPermission['subject'],
            $rawPermission['publicitment']
        );
    }
}
