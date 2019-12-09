<?php

namespace App\Application\Gallery\Query\V1;

use N3ttech\Messaging\Message\Domain\Message;

class ListAllAssetByGalleryHandler extends AssetQueryHandler
{
    /**
     * @param ListAllAssetByGallery $query
     */
    public function run(Message $query): void
    {
        $this->query->listAllAssetByGallery($query);
    }
}