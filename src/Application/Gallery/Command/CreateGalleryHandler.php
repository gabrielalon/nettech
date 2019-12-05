<?php

namespace App\Application\Gallery\Command;

use App\Domain\Model\Gallery\Gallery;
use N3ttech\Messaging\Message\Domain\Message;
use N3ttech\Valuing as VO;

class CreateGalleryHandler extends GalleryCommandHandler
{
    /**
     * @param CreateGallery $command
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function run(Message $command): void
    {
        $this->repository->save(Gallery::createNewGallery(
            VO\Identity\Uuid::fromIdentity($command->getUuid()),
            VO\Char\Text::fromString($command->getName()),
            VO\Char\Text::fromString($command->getSource()),
            Gallery\Assets::fromArray($command->getAssets())
        ));
    }
}
