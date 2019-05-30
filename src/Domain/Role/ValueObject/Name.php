<?php

namespace App\Domain\Role\ValueObject;

use Assert\Assertion;

final class Name
{
    private $value;

    public function __construct(string $value)
    {
        Assertion::notEmpty($value, 'Name cannot be empty');

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
