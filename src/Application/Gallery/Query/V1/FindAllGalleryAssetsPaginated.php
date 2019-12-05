<?php

namespace App\Application\Gallery\Query\V1;

class FindAllGalleryAssetsPaginated extends AssetQuery
{
    /** @var string */
    private $uuid;

    /** @var integer */
    private $page;

    /** @var integer */
    private $limit;

    /** @var integer */
    private $total;

    /**
     * FindAllGalleryAssetsPaginated constructor.
     * @param string $uuid
     * @param int $page
     * @param int $limit
     */
    public function __construct(string $uuid, int $page = 1, int $limit = 5)
    {
        $this->uuid = $uuid;
        $this->page = $page;
        $this->limit = $limit;
        $this->setTotal(0);
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
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
