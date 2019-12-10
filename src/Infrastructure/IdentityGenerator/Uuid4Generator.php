<?php

namespace App\Infrastructure\IdentityGenerator;

class Uuid4Generator implements IdentityGeneratorInterface
{
    /**
     * {@inheritdoc}
     */
    public function generate(): string
    {
        return \Ramsey\Uuid\Uuid::uuid4()->toString();
    }
}
