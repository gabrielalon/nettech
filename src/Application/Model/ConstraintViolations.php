<?php

namespace App\Application\Model;

use App\Infrastructure\Common\AbstractCollection;
use Symfony\Component\Validator\ConstraintViolation as ValidatorConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ConstraintViolations extends AbstractCollection
{
    public static function fromViolations(ConstraintViolationListInterface $violations): self
    {
        $items = [];

        /** @var ValidatorConstraintViolation $violation */
        foreach ($violations as $violation) {
            $items[] = new ConstraintViolation($violation->getPropertyPath(), $violation->getMessage());
        }

        return new static($items);
    }

    protected function isValid($value): bool
    {
        return $value instanceof ConstraintViolation;
    }
}
