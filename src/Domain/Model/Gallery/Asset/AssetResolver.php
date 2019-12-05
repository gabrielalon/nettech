<?php

namespace App\Domain\Model\Gallery\Asset;

use App\Domain\Model\Gallery\Gallery;
use App\Domain\Model\Gallery\Asset;

abstract class AssetResolver
{
    private $resolver = null;

    /**
     * MediaResolver constructor.
     * @param AssetResolver|null $resolver
     */
    public function __construct(?AssetResolver $resolver = null)
    {
        $this->resolver = $resolver;
    }

    /**
     * @return string[]
     */
    abstract protected function supportedExtensions(): array;

    /**
     * @param string $media
     * @return Asset
     */
    abstract protected function retrieveAsset(string $media): Asset;

    /**
     * @param string $media
     * @return Asset
     * @throws \Assert\AssertionFailedException
     */
    public function resolveAsset(string $media): Asset
    {
        if (true === $this->isExtensionSupported($media)) {
            return $this->retrieveAsset($media);
        }

        if (null === $this->resolver) {
            return Gallery\Video::fromVideo($media);
        }

        return $this->resolver->resolveAsset($media);
    }

    /**
     * @param string $media
     * @return bool
     */
    private function isExtensionSupported(string $media): bool
    {
        return in_array($this->resolveExtension($media), $this->supportedExtensions(), true);
    }

    /**
     * @param string $media
     * @return string
     */
    private function resolveExtension(string $media): string
    {
        return pathinfo($media, PATHINFO_EXTENSION);
    }
}

