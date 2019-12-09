<?php

namespace App\Application\Gallery\Query\V1;

use N3ttech\Messaging\Message\Domain\Message;

class FindOneGalleryByNameHandler extends GalleryQueryHandler
{
    /**
     * @param FindOneGalleryByName $query
     */
    public function run(Message $query): void
    {
        $this->query->findOneByName($query);
    }
}
