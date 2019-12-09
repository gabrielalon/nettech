<?php

namespace App\Domain\Model\Gallery\Gallery;

use App\Domain\Model\Gallery\Asset;
use Assert;

final class Assets extends \ArrayIterator
{
    /**
     * @param Asset $media
     */
    public function add(Asset $media): void
    {
        $this->append($media);
    }

    /**
     * @param string $media
     *
     * @return Asset
     */
    public function get(string $media): Asset
    {
        if (false === $this->offsetExists($media)) {
            throw new Assert\InvalidArgumentException('Not found media: '.$media, $media);
        }

        return $this->offsetGet($media);
    }

    /**
     * @param Assets $other
     *
     * @return bool
     */
    public function equals($other): bool
    {
        if (false == $other instanceof Assets) {
            return false;
        }

        return $other->getArrayCopy() == $this->getArrayCopy();
    }

    /**
     * @param array $data
     * @return Assets
     * @throws Assert\AssertionFailedException
     */
    public static function fromArray(array $data): Assets
    {
        $assetResolver = new Asset\ImageResolver(new Asset\VideoResolver());

        $assets = new Assets();

        foreach ($data as $asset) {
            $assets->add($assetResolver->resolveAsset($asset));
        }

        return $assets;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        $data = [];

        /** @var Asset $media */
        foreach ($this->getArrayCopy() as $media) {
            $data[] = $media->toString();
        }

        return $data;
    }
}

