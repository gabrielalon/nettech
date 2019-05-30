<?php

declare(strict_types=1);

namespace App\Domain\User\Service;

use App\Domain\User\Symfony\Bridge\PasswordEncoderUserStub;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoderService
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function encode(string $password): string
    {
        $stub = new PasswordEncoderUserStub($password);

        return $this->passwordEncoder->encodePassword($stub, $password);
    }
}
