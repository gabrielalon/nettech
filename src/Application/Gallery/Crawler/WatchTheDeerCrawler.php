<?php

namespace App\Application\Gallery\Crawler;

use Symfony\Component\DomCrawler\Crawler;

class WatchTheDeerCrawler implements RemoteCrawler
{
    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return 'watch-the-deer';
    }

    /**
     * @inheritDoc
     */
    public function url(): string
    {
        return 'http://watchthedeer.com/photos';
    }

    /**
     * @inheritDoc
     */
    public function galleries(): array
    {
        $crawler = new Crawler($this->url());

        $result = $crawler
            ->filterXpath('//img')
            ->extract(array('src'));
    }
}