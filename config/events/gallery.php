<?php

use App\Application\Gallery\Event;

return [
    Event\NewGalleryCreated::class => [\App\Infrastructure\Projection\Gallery\DoctrineGalleryProjector::class],
];
