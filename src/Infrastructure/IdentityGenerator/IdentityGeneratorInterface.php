<?php

namespace App\Infrastructure\IdentityGenerator;

interface IdentityGeneratorInterface
{
    public function generate(): string;
}
