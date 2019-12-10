<?php

namespace App\Infrastructure\Persist\Gallery;

use App\Domain\Model\Gallery\Gallery;
use N3ttech\Messaging\Aggregate\AggregateRoot;
use N3ttech\Messaging\Aggregate\Persist\AggregateRepository;
use N3ttech\Valuing as VO;

class GalleryRepository extends AggregateRepository
{
    /**
     * {@inheritdoc}
     */
    public function getAggregateRootClass(): string
    {
        return Gallery::class;
    }

    /**
     * @param Gallery $gallery
     *
     * @throws \Exception
     */
    public function save(Gallery $gallery): void
    {
        $this->saveAggregateRoot($gallery);
    }

    /**
     * @param string $uuid
     *
     * @return AggregateRoot|Gallery
     *
     * @throws \Assert\AssertionFailedException
     */
    public function find(string $uuid): AggregateRoot
    {
        return $this->findAggregateRoot(VO\Identity\Uuid::fromIdentity($uuid));
    }
}
