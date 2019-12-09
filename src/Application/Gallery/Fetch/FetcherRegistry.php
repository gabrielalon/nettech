<?php

namespace App\Application\Gallery\Fetch;

use Assert\Assertion;

class FetcherRegistry
{
    /** @var Fetcher[] */
    private $fetchers;

    /**
     * @param Fetcher $fetcher
     */
    public function register(Fetcher $fetcher): void
    {
        $this->fetchers[$fetcher->name()] = $fetcher;
    }

    /**
     * @param string $name
     * @return Fetcher
     * @throws \Assert\AssertionFailedException
     */
    public function get(string $name): Fetcher
    {
        Assertion::notEmptyKey($this->fetchers, $name, 'Gallery fetcher "%s" was not found.');

        return $this->fetchers[$name];
    }

    /**
     * @return Fetcher[]
     */
    public function getFetchers(): array
    {
        return $this->fetchers;
    }
}