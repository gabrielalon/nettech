<?php

namespace App\Application\Security\Jwt\Util;

use Firebase\JWT\JWT;

class JwtUtil implements JwtUtilInterface
{
    private $jwtAlgorithm;
    private $jwtPrivateKey;
    private $jwtPublicKey;

    public function __construct(
        string $jwtAlgorithm = '',
        string $jwtPrivateKey = '',
        string $jwtPublicKey = ''
    ) {
        $this->jwtAlgorithm = $jwtAlgorithm;
        $this->jwtPrivateKey = $jwtPrivateKey;
        $this->jwtPublicKey = $jwtPublicKey;
    }

    public function encode(iterable $tokenData): string
    {
        return JWT::encode($tokenData, file_get_contents($this->jwtPrivateKey), $this->jwtAlgorithm);
    }

    public function decode(string $tokenString): \stdClass
    {
        return JWT::decode($tokenString, file_get_contents($this->jwtPublicKey), [$this->jwtAlgorithm]);
    }
}
