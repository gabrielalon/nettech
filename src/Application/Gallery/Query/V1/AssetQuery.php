<?php

namespace App\Application\Gallery\Query\V1;

use App\Application\Gallery\Query\ReadModel;
use N3ttech\Messaging\Query\Exception;

abstract class AssetQuery extends AbstractQuery
{
    /** @var ReadModel\Entity\AssetCollection */
    private $collection;

    /**
     * @throws Exception\ResourceNotFoundException
     *
     * @return ReadModel\Entity\Asset
     */
    public function getAsset(): ReadModel\Entity\Asset
    {
        $this->initializeCollection();

        if (0 === $this->collection->count()) {
            throw new Exception\ResourceNotFoundException('Asset not found');
        }

        return $this->collection->current();
    }

    /**
     * @param ReadModel\Entity\Asset $entry
     */
    public function addAsset(ReadModel\Entity\Asset $entry): void
    {
        $this->initializeCollection();

        $this->collection->add($entry);
    }

    /**
     * @return ReadModel\Entity\AssetCollection
     */
    public function getCollection(): ReadModel\Entity\AssetCollection
    {
        $this->initializeCollection();

        return $this->collection;
    }

    private function initializeCollection(): void
    {
        if (null === $this->collection) {
            $this->collection = new ReadModel\Entity\AssetCollection();
        }
    }
}
