<?php

namespace App\Application\Gallery\Seek;

use App\Application\Gallery\Query;
use MyCLabs\Enum\Enum as EnumOrderField;

class AssetSearcher extends FinderSearcher
{
    /**
     * @param string $galleryUuid
     */
    public function setGallery(string $galleryUuid): void
    {
        $this->setParam('gallery_uuid', $galleryUuid);
    }

    /**
     * @inheritDoc
     */
    protected function defaultOrderField(): EnumOrderField
    {
        return Enum\AssetOrder::ID();
    }

    /**
     * @return array
     */
    public function orderFields(): array
    {
        return Enum\AssetOrder::values();
    }

    /**
     * @inheritDoc
     */
    protected function defaultOrderSort(): Enum\OrderSort
    {
        return Enum\OrderSort::ASC();
    }

    public function performSearch(): void
    {
        $this->init();

        $query = new Query\V1\ListAllAssetByGallery(
            $this->currentPage(),
            $this->defaultLimit(),
            $this->params->get('gallery_uuid')
        );
        $query->setOrderField($this->orderField());
        $query->setOrderSort($this->orderSort());

        $this->ask($query);

        $this->setCurrentLp($query->getOffset());
        $this->setTotalPages($query->getTotalCounter());
        $this->setCollection($query->getCollection());
    }
}