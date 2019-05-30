<?php

namespace App\Infrastructure\Testing\Constraint;

use PHPUnit\Framework\Constraint\Constraint;

class InArray extends Constraint
{
    /**
     * @var array
     */
    private $array;

    /**
     * @var bool
     */
    private $strictType;

    public function __construct(iterable $array, bool $strictType = false)
    {
        parent::__construct();

        if (\is_array($array)) {
            $this->array = $array;
        } elseif ($array instanceof \IteratorAggregate) {
            $this->array = iterator_to_array($array->getIterator());
        } elseif ($array instanceof \Iterator) {
            $this->array = iterator_to_array($array);
        }

        $this->strictType = $strictType;
    }

    public function matches($other)
    {
        return \in_array($other, $this->array, $this->strictType);
    }

    public function toString()
    {
        return 'in array';
    }
}
