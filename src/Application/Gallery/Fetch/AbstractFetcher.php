<?php

namespace App\Application\Gallery\Fetch;

abstract class AbstractFetcher implements Fetcher
{
    /**
     * @param string $url
     * @return string
     */
    protected function retrieveContent(string $url): string
    {
        try
        {
            $fileName = $url;

            $fp = @fopen($fileName, "rb");
            if ( !$fp ) {
                throw new \Exception('File open failed.');
            }

            $str = stream_get_contents($fp);
            fclose($fp);

            return $str;
        } catch ( \Exception $e ) {
            return '';
        }
    }
}