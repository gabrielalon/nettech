<?php

namespace App\Application\Gallery\Event;

use N3ttech\Messaging\Aggregate\EventBridge\AggregateChanged;
use N3ttech\Valuing as VO;

abstract class GalleryEvent extends AggregateChanged
{
    /**
     * @return VO\Identity\Uuid
     *
     * @throws \Assert\AssertionFailedException
     */
    public function galleryUuid(): VO\Identity\Uuid
    {
        return VO\Identity\Uuid::fromIdentity($this->aggregateId());
    }
}
