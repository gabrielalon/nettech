<?php

namespace App\Application\Security\Jwt;

use App\Domain\User\ReadModel\Entity\User;

interface JwtUserInterface
{
    public function get(): User;
}
