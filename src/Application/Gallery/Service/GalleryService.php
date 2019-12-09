<?php

namespace App\Application\Gallery\Service;

use App\Application\Gallery\Query\ReadModel\Entity\Gallery;
use App\Infrastructure\IdentityGenerator\IdentityGeneratorInterface;
use N3ttech\Messaging\Query\Exception;

class GalleryService
{
    /** @var GalleryQueryManager */
    private $query;

    /** @var GalleryCommandManager */
    private $command;

    /** @var IdentityGeneratorInterface */
    private $identityGenerator;

    /**
     * GalleryService constructor.
     * @param GalleryQueryManager $query
     * @param GalleryCommandManager $command
     * @param IdentityGeneratorInterface $identityGenerator
     */
    public function __construct(
        GalleryQueryManager $query,
        GalleryCommandManager $command,
        IdentityGeneratorInterface $identityGenerator
    ) {
        $this->query = $query;
        $this->command = $command;
        $this->identityGenerator = $identityGenerator;
    }

    /**
     * @param string $name
     * @param string $source
     * @param array $assets
     * @return Gallery
     * @throws \Exception
     */
    public function create(string $name, string $source, array $assets = []): Gallery
    {
        try {
            return $this->query->findOneGalleryByName($name);
        } catch (Exception\ResourceNotFoundException $e) {
            $uuid = $this->identityGenerator->generate();
            $this->command->create($uuid, $name, $source, $assets);
        }

        return $this->query->findOneGalleryByUuid($uuid);
    }
}