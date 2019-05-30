<?php

namespace App\Infrastructure\Permission\Loader;

use App\Infrastructure\Permission\Loader\Exception\ActionNotFoundException;
use App\Infrastructure\Permission\Loader\Exception\SubjectNotFoundException;
use Symfony\Component\Yaml\Yaml;

class YamlPermissionLoader implements LoaderInterface
{
    private $permissions;

    public function __construct(string $filePath)
    {
        $this->permissions = Yaml::parseFile($filePath);
    }

    /**
     * @throws SubjectNotFoundException
     * @throws ActionNotFoundException
     */
    public function get(string $subject, string $action): array
    {
        if (false === isset($this->permissions[$subject])) {
            throw new SubjectNotFoundException(
                sprintf('[Permissions] Subject %s has not been found', $subject)
            );
        }

        if (false === isset($this->permissions[$subject][$action])) {
            throw new ActionNotFoundException(
                sprintf('[Permissions] Action %s has not been found', $action)
            );
        }

        $permission = $this->permissions[$subject][$action];

        return [
            'subject' => $subject,
            'action'  => $action,
            'public'  => (bool) $permission['public'],
        ];
    }
}
