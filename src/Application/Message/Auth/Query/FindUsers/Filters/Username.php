<?php

namespace App\Application\Message\Auth\Query\FindUsers\Filters;

class Username
{
    /**
     * @var object
     */
    private $username;

    public function __construct(object $username)
    {
        $this->username = $username;
    }

    public function hasEquals(): bool
    {
        return property_exists($this->username, 'equals');
    }

    public function getEquals(): ?string
    {
        return $this->username->equals ?? null;
    }
}
