<?php

namespace App\Infrastructure\IdentityGenerator;

class Uuid4Generator implements IdentityGeneratorInterface
{
    /**
     * @inheritDoc
     */
    public function generate(): string
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}
