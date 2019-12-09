<?php

namespace App\Application\Gallery\Fetch;

use Symfony\Component\DomCrawler\Crawler;

class WatchTheDeerFetcher extends AbstractFetcher
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
    public function url(string $address = ''): string
    {
        return 'http://watchthedeer.com' . DIRECTORY_SEPARATOR . ltrim($address, DIRECTORY_SEPARATOR);
    }

    /**
     * @param string $asset
     * @param string $key
     * @param string $link
     */
    private function asset(string &$asset, string $key, string $link): void
    {
        $asset = $link . mb_substr($asset, 3);
    }

    /**
     * @inheritDoc
     */
    public function galleries(): array
    {
        $url = $this->url('photos');
        $cnt = $this->retrieveContent($url);

        try {
            $data = (new Crawler($cnt))
                ->filterXPath('//html/body/form/div/div/div/ul/li/a')
                ->each(function (Crawler $node, $i) {
                    $links = $node->extract(['href']);
                    $link = str_replace(['.aspx', '..', ' '], ['', '', '%20'], current($links));
                    return [$node->text(), $link];
                });
        }
        catch (\Exception $e) {
            return [];
        }

        $galleries = [];
        foreach ($data as $gallery) {
            list($galleryName, $link) = $gallery;

            $prefix = str_replace('viewer', '', $link);
            preg_match_all('/= \'[\w_-]+.jpg/i', $this->retrieveContent($this->url($link)), $matches);

            $assets = $matches[0];
            array_walk($assets, [$this, 'asset'], $prefix);

            $galleries[$galleryName] = $assets;
        }

        return $galleries;
    }
}