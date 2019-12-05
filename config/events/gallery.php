<?php

use App\Application\Gallery\Event;

return [
    Event\NewGalleryCreated::class => [\App\Domain\Model\Gallery\Projection\GalleryProjector::class],
];
