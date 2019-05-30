<?php

namespace App\Infrastructure\Permission\Loader;

use App\Infrastructure\Permission\Loader\Exception\ActionNotFoundException;
use App\Infrastructure\Permission\Loader\Exception\SubjectNotFoundException;

interface LoaderInterface
{
    /**
     * @throws SubjectNotFoundException
     * @throws ActionNotFoundException
     */
    public function get(string $subject, string $action): array;
}
