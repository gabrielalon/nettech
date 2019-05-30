<?php

namespace App\Application\Model;

class ConstraintViolation
{
    /**
     * @var string
     */
    private $property;

    /**
     * @var string
     */
    private $message;

    public function __construct(string $property, string $message)
    {
        $this->property = $property;
        $this->message = $message;
    }

    public function getProperty(): string
    {
        return $this->property;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
