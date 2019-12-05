<?php

namespace App\Application\Gallery\Query\V1;

use App\Application\Gallery\Query\AssetQuery;

abstract class AssetQueryHandler implements \N3ttech\Messaging\Query\QueryHandling\QueryHandler
{
    /** @var AssetQuery */
    protected $query;

    /**
     * @param AssetQuery $query
     */
    public function __construct(AssetQuery $query)
    {
        $this->query = $query;
    }
}
