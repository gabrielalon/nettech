<?php

namespace App\Domain\User\ValueObject;

use Assert\Assertion;

final class LastName
{
    private $value;

    public function __construct(string $value)
    {
        Assertion::notEmpty($value);

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
