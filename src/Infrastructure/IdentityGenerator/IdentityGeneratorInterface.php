<?php

declare(strict_types=1);

namespace App\Infrastructure\IdentityGenerator;

interface IdentityGeneratorInterface
{
    public function generate(): string;
}
