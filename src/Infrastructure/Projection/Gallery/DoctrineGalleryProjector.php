<?php

namespace App\Infrastructure\Projection\Gallery;

use App\Application\Gallery\Event;
use App\Domain\Model\Gallery\Projection\GalleryProjector;
use App\Infrastructure\Doctrine\DatabaseConnected;

class DoctrineGalleryProjector extends DatabaseConnected implements GalleryProjector
{
    /**
     * @inheritDoc
     */
    public function onNewGalleryCreated(Event\NewGalleryCreated $event): void
    {

    }
}
