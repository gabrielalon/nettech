<?php

namespace App\Infrastructure\Projection\Gallery;

use App\Application\Gallery\Event;
use App\Domain\Model\Gallery\Asset;
use App\Domain\Model\Gallery\Projection\GalleryProjector;
use App\Infrastructure\Doctrine\DatabaseConnected;

class DoctrineGalleryProjector extends DatabaseConnected implements GalleryProjector
{
    /**
     * @inheritDoc
     */
    public function onNewGalleryCreated(Event\NewGalleryCreated $event): void
    {
        if (true === $this->handleGalleryCreation($event)) {
            $this->handleGalleryAssetsCreation($event);
        }
    }

    /**
     * @param Event\NewGalleryCreated $event
     * @return bool
     * @throws \Assert\AssertionFailedException
     * @throws \Doctrine\DBAL\DBALException
     */
    private function handleGalleryCreation(Event\NewGalleryCreated $event): bool
    {
        $creationDate = date_timestamp_set(date_create(), $event->galleryCreationDate()->raw());

        $query = '
            INSERT `gallery` (`uuid`, `source`, `name`, `created_at`)
            VALUES (:uuid, :source, :name, :created_at)
        ';

        $statement = $this->connection->prepare($query);

        return $statement->execute([
            'uuid' => $event->galleryUuid()->toString(),
            'source' => $event->gallerySource()->toString(),
            'name' => $event->galleryName()->toString(),
            'created_at' => \DateTimeImmutable::createFromMutable($creationDate)->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @param Event\NewGalleryCreated $event
     * @throws \Assert\AssertionFailedException
     * @throws \Doctrine\DBAL\DBALException
     */
    private function handleGalleryAssetsCreation(Event\NewGalleryCreated $event): void
    {
        $query = '
            INSERT `gallery_asset` (`gallery_uuid`, `type`, `filename`)
            VALUES (:gallery_uuid, :type, :filename)
        ';

        /** @var Asset $asset */
        foreach ($event->galleryAssets()->getArrayCopy() as $asset) {
            $statement = $this->connection->prepare($query);
            $statement->execute([
                'gallery_uuid' => $event->galleryUuid()->toString(),
                'type' => $asset->getType()->getValue(),
                'filename' => $asset->toString()
            ]);
        }
    }
}
