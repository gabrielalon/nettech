<?php

namespace App\Application\Security\Jwt\Util;

interface JwtUtilInterface
{
    public function encode(iterable $tokenData): string;

    public function decode(string $tokenString): \stdClass;
}
