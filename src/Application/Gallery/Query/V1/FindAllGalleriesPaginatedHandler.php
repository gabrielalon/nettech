<?php

namespace App\Application\Gallery\Query\V1;

use N3ttech\Messaging\Message\Domain\Message;

class FindAllGalleriesPaginatedHandler extends GalleryQueryHandler
{
    /**
     * @param FindAllGalleriesPaginated $query
     */
    public function run(Message $query): void
    {
        $this->query->findAllPaginated($query);
    }
}
