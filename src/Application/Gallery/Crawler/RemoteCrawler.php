<?php

namespace App\Application\Gallery\Crawler;

interface RemoteCrawler
{
    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function url(): string;

    /**
     * @return string[][]
     */
    public function galleries(): array;
}