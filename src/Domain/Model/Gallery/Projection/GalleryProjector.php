<?php

namespace App\Domain\Model\Gallery\Projection;

use App\Application\Gallery\Event;
use N3ttech\Messaging\Message\EventSourcing\EventProjector;

interface GalleryProjector extends EventProjector
{
    /**
     * @param Event\NewGalleryCreated $event
     */
    public function onNewGalleryCreated(Event\NewGalleryCreated $event): void;
}
