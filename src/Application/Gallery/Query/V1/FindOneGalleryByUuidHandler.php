<?php

namespace App\Application\Gallery\Query\V1;

use N3ttech\Messaging\Message\Domain\Message;

class FindOneGalleryByUuidHandler extends GalleryQueryHandler
{
    /**
     * @param FindOneGalleryByUuid $query
     */
    public function run(Message $query): void
    {
        $this->query->findOneByUuid($query);
    }
}
