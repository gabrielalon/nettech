<?php

namespace App\Infrastructure\Common;

abstract class AbstractCollection implements \IteratorAggregate, \ArrayAccess
{
    private $values;

    public function __construct(array $values = [])
    {
        foreach ($values as $key => $value) {
            if (!$this->isValid($value)) {
                throw new \InvalidArgumentException(
                    sprintf(
                    'Invalid item on key %d of collection "%s"',
                    $key,
                    \get_class($this)
                )
                );
            }
        }

        $this->values = $values;
    }

    abstract protected function isValid($value): bool;

    public function getIterator(): \Traversable
    {
        return new \ArrayIterator($this->values);
    }

    public function offsetExists($offset)
    {
        return \array_key_exists($offset, $this->values);
    }

    public function offsetGet($offset)
    {
        return $this->values[$offset];
    }

    public function offsetSet($offset, $value)
    {
        $this->values[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->values[$offset]);
    }
}
