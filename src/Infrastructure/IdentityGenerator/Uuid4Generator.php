<?php

declare(strict_types=1);

namespace App\Infrastructure\IdentityGenerator;

use Ramsey\Uuid\Uuid;

class Uuid4Generator implements IdentityGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
