<?php

namespace App\Application\Gallery\Query\V1;

abstract class AbstractQuery extends \N3ttech\Messaging\Query\Query\Query
{
    /** @var string */
    private $orderSort;

    /** @var string */
    private $orderField;

    /** @var int */
    private $totalCounter;

    /**
     * @return int
     */
    public function getTotalCounter(): int
    {
        $this->initializeTotalCounter();

        return $this->totalCounter;
    }

    /**
     * @param int $totalCounter
     */
    public function setTotalCounter(int $totalCounter): void
    {
        $this->totalCounter = $totalCounter;
    }

    private function initializeTotalCounter(): void
    {
        if (null === $this->totalCounter) {
            $this->setTotalCounter(0);
        }
    }

    /**
     * @return string
     */
    public function getOrderSort(): string
    {
        return $this->orderSort;
    }

    /**
     * @param string $orderSort
     */
    public function setOrderSort(string $orderSort): void
    {
        $this->orderSort = $orderSort;
    }

    /**
     * @return string
     */
    public function getOrderField(): string
    {
        return $this->orderField;
    }

    /**
     * @param string $orderField
     */
    public function setOrderField(string $orderField): void
    {
        $this->orderField = $orderField;
    }
}
