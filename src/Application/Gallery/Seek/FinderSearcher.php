<?php

namespace App\Application\Gallery\Seek;

use MyCLabs\Enum\Enum as EnumOrderField;
use N3ttech\Messaging\Manager\QueryManager;
use Symfony\Component\HttpFoundation\ParameterBag;

abstract class FinderSearcher extends QueryManager
{
    const LIMIT = 5;
    const FIRST_PAGE = 1;

    /** @var int */
    protected $page;

    /** @var ParameterBag */
    protected $params;

    /** @var string */
    protected $orderField;

    /** @var string */
    protected $orderSort;

    /** @var int */
    protected $totalPages;

    /** @var int */
    protected $currentLp;

    /** @var \ArrayIterator */
    protected $collection;

    protected function init(): void
    {
        if (null === $this->orderField) {
            $this->setOrderField($this->defaultOrderField()->getValue());
        }

        if (null === $this->orderSort) {
            $this->setOrderSort($this->defaultOrderSort()->getValue());
        }
    }

    /**
     * @param int $page
     */
    public function setPage(int $page): void
    {
        $this->page = $page;
    }

    /**
     * @param string|null $orderField
     */
    public function setOrderField(?string $orderField = null): void
    {
        $this->orderField = $orderField;
    }

    /**
     * @return string
     */
    public function orderField(): string
    {
        return $this->orderField;
    }

    /**
     * @param string|null $orderSort
     */
    public function setOrderSort(?string $orderSort = null): void
    {
        if (true === Enum\OrderSort::isValid($orderSort)) {
            $this->orderSort = $orderSort;
        }
    }

    /**
     * @return string
     */
    public function orderSort(): string
    {
        return $this->orderSort;
    }

    /**
     * @param string $key
     * @param $value
     */
    public function setParam(string $key, $value): void
    {
        if (null === $this->params) {
            $this->params = new ParameterBag();
        }

        $this->params->set($key, $value);
    }

    /**
     * @return EnumOrderField
     */
    abstract protected function defaultOrderField(): EnumOrderField;

    /**
     * @return array
     */
    abstract public function orderFields(): array;

    /**
     * @return Enum\OrderSort
     */
    abstract protected function defaultOrderSort(): Enum\OrderSort;

    /**
     * @return array
     */
    public function orderDirections(): array
    {
        return Enum\OrderSort::values();
    }

    /**
     * @return int
     */
    public function currentPage(): int
    {
        if (null === $this->page) {
            return self::FIRST_PAGE;
        }

        return $this->page;
    }

    /**
     * @return int
     */
    public function defaultLimit(): int
    {
        return self::LIMIT;
    }

    /**
     * @return int
     */
    public function getTotalPages(): int
    {
        return $this->totalPages;
    }

    /**
     * @param int $totalCounter
     */
    public function setTotalPages(int $totalCounter): void
    {
        $this->totalPages = 1 + floor($totalCounter / $this->defaultLimit());
    }

    /**
     * @return int
     */
    public function currentLp(): int
    {
        $lp = $this->currentLp;
        $this->moveLp();

        return $lp;
    }

    private function moveLp(): void
    {
        ++$this->currentLp;
    }

    /**
     * @param int $offset
     */
    public function setCurrentLp(int $offset): void
    {
        $this->currentLp = 1 + $offset;
    }

    /**
     * @return \ArrayIterator
     */
    public function getCollection(): \ArrayIterator
    {
        return $this->collection;
    }

    /**
     * @param \ArrayIterator $collection
     */
    public function setCollection(\ArrayIterator $collection): void
    {
        $this->collection = $collection;
    }

    abstract public function performSearch(): void;
}
