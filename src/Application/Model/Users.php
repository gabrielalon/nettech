<?php

namespace App\Application\Model;

use App\Infrastructure\Common\AbstractCollection;

class Users extends AbstractCollection
{
    protected function isValid($value): bool
    {
        return $value instanceof User;
    }
}
