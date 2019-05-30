<?php

namespace App\Domain\Shared\ValueObject;

use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;
use Assert\Assertion;

abstract class IncrementalIdentity implements IdentifyInterface
{
    private $value;

    public function __construct(string $value)
    {
        Assertion::greaterThan($value, 0, 'Incorrect incremental identity');
        $this->value = $value;
    }

    public function equals(IdentifyInterface $other): bool
    {
        return $this->__toString() === $other->__toString();
    }

    public function __toString()
    {
        return $this->value;
    }
}
