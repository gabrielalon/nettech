<?php

namespace App\Application\Gallery\Query\V1;

use N3ttech\Messaging\Message\Domain\Message;

class ListAllGalleryBySourceHandler extends GalleryQueryHandler
{
    /**
     * @param ListAllGalleryBySource $query
     */
    public function run(Message $query): void
    {
        $this->query->listAllBySource($query);
    }
}
