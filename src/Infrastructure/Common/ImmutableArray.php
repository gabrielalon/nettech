<?php

declare(strict_types=1);

namespace App\Infrastructure\Common;

use App\Infrastructure\Common\Exception\ImmutableArrayException;

abstract class ImmutableArray extends \SplFixedArray
{
    public function __construct(array $items = [])
    {
        parent::__construct(\count($items));

        $i = 0;
        foreach ($items as $item) {
            $this->guardType($item);
            parent::offsetSet($i++, $item);
        }
    }

    /**
     * Throw when the item is not an instance of the accepted type.
     *
     * @param $item
     *
     * @throws \InvalidArgumentException
     */
    abstract protected function guardType($item): void;

    final public function count(): int
    {
        return parent::count();
    }

    final public function current()
    {
        return parent::current();
    }

    final public function key(): int
    {
        return parent::key();
    }

    final public function next(): void
    {
        parent::next();
    }

    final public function rewind(): void
    {
        parent::rewind();
    }

    final public function valid(): bool
    {
        return parent::valid();
    }

    final public function offsetExists($offset): bool
    {
        return parent::offsetExists($offset);
    }

    final public function offsetGet($offset)
    {
        return parent::offsetGet($offset);
    }

    final public function offsetSet($offset, $value): void
    {
        throw new ImmutableArrayException('This array is immutable, you are not allowed to set values in it');
    }

    final public function offsetUnset($offset): void
    {
        throw new ImmutableArrayException('This array is immutable, you are not allowed to unset values in it');
    }
}
