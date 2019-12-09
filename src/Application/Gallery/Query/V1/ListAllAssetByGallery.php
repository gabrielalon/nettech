<?php

namespace App\Application\Gallery\Query\V1;

class ListAllAssetByGallery extends AssetQuery
{
    use QueryListed;

    /** @var string */
    private $galleryUuid;

    /**
     * ListAllAssetByGallery constructor.
     * @param int $page
     * @param int $limit
     * @param string $galleryUuid
     */
    public function __construct(int $page, int $limit, string $galleryUuid)
    {
        $this->setPage($page);
        $this->setLimit($limit);
        $this->galleryUuid = $galleryUuid;
    }

    /**
     * @return string
     */
    public function getGalleryUuid(): string
    {
        return $this->galleryUuid;
    }
}