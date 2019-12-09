<?php

namespace App\Application\Gallery\Query\V1;

class ListAllGalleryBySource extends GalleryQuery
{
    use QueryListed;

    /** @var string */
    private $source;

    /**
     * ListAllGalleryBySource constructor.
     * @param int $page
     * @param int $limit
     * @param string $source
     */
    public function __construct(int $page, int $limit, string $source)
    {
        $this->setPage($page);
        $this->setLimit($limit);
        $this->source = $source;
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->source;
    }
}