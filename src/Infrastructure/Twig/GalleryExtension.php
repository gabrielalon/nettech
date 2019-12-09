<?php

namespace App\Infrastructure\Twig;

use App\Application\Gallery\Fetch\FetcherRegistry;
use App\Application\Gallery\Query\ReadModel\Entity\Asset;
use App\Application\Gallery\Query\ReadModel\Entity\Gallery;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class GalleryExtension extends AbstractExtension
{
    /** @var FetcherRegistry */
    private $fetcherRegistry;

    /**
     * GalleryExtension constructor.
     * @param FetcherRegistry $fetcherRegistry
     */
    public function __construct(FetcherRegistry $fetcherRegistry)
    {
        $this->fetcherRegistry = $fetcherRegistry;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('asset_url', [$this, 'assetUrl']),
        ];
    }

    /**
     * @param Gallery $gallery
     * @param Asset $asset
     * @return string
     * @throws \Assert\AssertionFailedException
     */
    public function assetUrl(Gallery $gallery, Asset $asset): string
    {
        return $this->fetcherRegistry->get($gallery->getSource())->url($asset->getFilename());
    }
}