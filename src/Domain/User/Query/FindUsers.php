<?php

namespace App\Domain\User\Query;

use App\Application\Message\Auth\Query\FindUsers\Filters;

class FindUsers
{
    /**
     * @var Filters
     */
    private $filters;

    public function __construct(Filters $filters)
    {
        $this->filters = $filters;
    }

    public function getFilters(): Filters
    {
        return $this->filters;
    }
}
