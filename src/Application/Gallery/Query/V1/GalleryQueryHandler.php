<?php

namespace App\Application\Gallery\Query\V1;

use App\Application\Gallery\Query\GalleryQuery;

abstract class GalleryQueryHandler implements \N3ttech\Messaging\Query\QueryHandling\QueryHandler
{
    /** @var GalleryQuery */
    protected $query;

    /**
     * @param GalleryQuery $query
     */
    public function __construct(GalleryQuery $query)
    {
        $this->query = $query;
    }
}
