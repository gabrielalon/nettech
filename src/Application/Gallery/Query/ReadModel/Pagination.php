<?php

namespace App\Application\Gallery\Query\ReadModel;

class Pagination
{
    /** @var integer */
    private $page;

    /** @var integer */
    private $total;

    /** @var \ArrayIterator */
    private $collection;

    /**
     * Pagination constructor.
     * @param int $page
     * @param int $total
     * @param \ArrayIterator $collection
     */
    public function __construct(int $page, int $total, \ArrayIterator $collection)
    {
        $this->page = $page;
        $this->total = $total;
        $this->collection = $collection;
    }

    /**
     * @return int
     */
    public function getPage(): int
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @return \ArrayIterator
     */
    public function getCollection(): \ArrayIterator
    {
        return $this->collection;
    }
}
