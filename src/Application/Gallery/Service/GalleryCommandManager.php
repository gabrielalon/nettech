<?php

namespace App\Application\Gallery\Service;

use App\Application\Gallery\Command;
use N3ttech\Messaging\Manager\CommandManager;

class GalleryCommandManager extends CommandManager
{
    /**
     * @param string $uuid
     * @param string $source
     * @param string $name
     * @param array $assets
     */
    public function create(string $uuid, string $source, string $name, array $assets): void
    {
        $this->command(new Command\CreateGallery($uuid, $source, $name, $assets));
    }
}
