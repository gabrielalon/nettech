<?php

namespace App\Domain\Country\ValueObject;

use Assert\Assertion;

final class Name
{
    /**
     * @var string
     */
    private $value;

    public function __construct(string $value)
    {
        Assertion::notEmpty($value, 'Name value cannot be an empty value');

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
