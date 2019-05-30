<?php

namespace App\Domain\Shared\ValueObject;

use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;
use Assert\Assertion;

abstract class UUID4Identity implements IdentifyInterface
{
    /**
     * @var string
     */
    protected $value;

    public function __construct(string $value)
    {
        Assertion::uuid($value);
        $this->value = $value;
    }

    public function equals(IdentifyInterface $other): bool
    {
        return $this->value === $other->__toString();
    }

    public function __toString()
    {
        return $this->getValue();
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
