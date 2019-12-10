<?php

namespace App\Application\Gallery\Fetch;

interface Fetcher
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @param string $address
     *
     * @return string
     */
    public function url(string $address = ''): string;

    /**
     * @return string[][]
     */
    public function galleries(): array;
}
