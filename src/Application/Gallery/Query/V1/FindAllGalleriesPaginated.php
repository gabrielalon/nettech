<?php

namespace App\Application\Gallery\Query\V1;

class FindAllGalleriesPaginated extends GalleryQuery
{
    /** @var integer */
    private $page;

    /** @var integer */
    private $limit;

    /** @var integer */
    private $total;

    /**
     * FindAllGalleriesPaginated constructor.
     * @param int $page
     * @param int $limit
     */
    public function __construct(int $page = 1, int $limit = 5)
    {
        $this->page = $page;
        $this->limit = $limit;
        $this->setTotal(0);
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
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        return $this->total;
    }

    /**
     * @param int $total
     */
    public function setTotal(int $total)
    {
        $this->total = $total;
    }
}
