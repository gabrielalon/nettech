<?php

namespace App\Infrastructure\PasswordHasher;

interface PasswordHasherInterface
{
    /**
     * @param string $password
     * @return string
     */
    public function hash(string $password): string;
}