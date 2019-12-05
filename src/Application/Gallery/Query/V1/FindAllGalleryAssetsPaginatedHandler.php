<?php

namespace App\Application\Gallery\Query\V1;

use N3ttech\Messaging\Message\Domain\Message;

class FindAllGalleryAssetsPaginatedHandler extends AssetQueryHandler
{
    /**
     * @param FindAllGalleryAssetsPaginated $query
     */
    public function run(Message $query): void
    {
        $this->query->findAllPaginated($query);
    }
}
