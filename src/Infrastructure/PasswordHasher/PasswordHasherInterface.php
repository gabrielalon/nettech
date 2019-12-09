<?php

namespace App\Infrastructure\PasswordHasher;

use Symfony\Component\Security\Core\Encoder\EncoderAwareInterface;

interface PasswordHasherInterface extends EncoderAwareInterface
{
    /**
     * @param string $password
     * @return string
     */
    public function hash(string $password): string;
}