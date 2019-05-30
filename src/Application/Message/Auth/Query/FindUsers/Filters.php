<?php

namespace App\Application\Message\Auth\Query\FindUsers;

use App\Application\Message\Auth\Query\FindUsers\Filters\Username;

class Filters
{
    /**
     * @var array
     */
    private $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function hasUsername(): bool
    {
        return \array_key_exists('username', $this->filters);
    }

    public function getUsername(): ?Username
    {
        return new Username((object) $this->filters['username']) ?? null;
    }
}
