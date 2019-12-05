<?php

namespace App\Domain\Model\Gallery\Asset;

use App\Domain\Model\Gallery\Gallery;
use App\Domain\Model\Gallery\Asset;

class ImageResolver extends AssetResolver
{
    /**
     * @param string $media
     * @return Asset
     * @throws \Assert\AssertionFailedException
     */
    public function retrieveAsset(string $media): Asset
    {
        return Gallery\Image::fromImage($media);
    }

    /**
     * @inheritDoc
     */
    protected function supportedExtensions(): array
    {
        return ['jpg', 'png', 'jpeg', 'tiff', 'webp'];
    }
}
