<?php

namespace App\Infrastructure\PasswordHasher;

class Sha1Hasher implements PasswordHasherInterface
{
    /**
     * @inheritDoc
     */
    public function hash(string $password): string
    {
        return sha1($password);
    }
}