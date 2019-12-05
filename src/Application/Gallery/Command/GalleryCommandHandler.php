<?php

namespace App\Application\Gallery\Command;

use App\Infrastructure\Persist\Gallery\GalleryRepository;

abstract class GalleryCommandHandler implements \N3ttech\Messaging\Command\CommandHandling\CommandHandler
{
    /** @var GalleryRepository */
    protected $repository;

    /**
     * @param GalleryRepository $repository
     */
    public function __construct(GalleryRepository $repository)
    {
        $this->repository = $repository;
    }
}
