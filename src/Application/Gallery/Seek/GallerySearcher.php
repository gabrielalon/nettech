<?php

namespace App\Application\Gallery\Seek;

use App\Application\Gallery\Query;
use MyCLabs\Enum\Enum as EnumOrderField;

class GallerySearcher extends FinderSearcher
{
    /**
     * @param string $source
     */
    public function setSource(string $source): void
    {
        $this->setParam('source', $source);
    }

    /**
     * @return string
     */
    public function getSource(): string
    {
        return $this->params->get('source');
    }

    /**
     * @inheritDoc
     */
    protected function defaultOrderField(): EnumOrderField
    {
        return Enum\GalleryOrder::CREATED_AT();
    }

    /**
     * @return array
     */
    public function orderFields(): array
    {
        return Enum\GalleryOrder::values();
    }

    /**
     * @inheritDoc
     */
    protected function defaultOrderSort(): Enum\OrderSort
    {
        return Enum\OrderSort::DESC();
    }

    public function performSearch(): void
    {
        $this->init();

        $query = new Query\V1\ListAllGalleryBySource(
            $this->currentPage(),
            $this->defaultLimit(),
            $this->getSource()
        );
        $query->setOrderField($this->orderField());
        $query->setOrderSort($this->orderSort());

        $this->ask($query);

        $this->setCurrentLp($query->getOffset());
        $this->setTotalPages($query->getTotalCounter());
        $this->setCollection($query->getCollection());
    }
}