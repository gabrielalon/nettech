<?php

namespace App\Tests\Application\Gallery\Command;

use App\Application\Gallery\Command;
use App\Application\Gallery\Event;
use App\Application\Gallery\Service;
use App\Domain\Model\Gallery\Gallery;
use App\Domain\Model\Gallery\Projection;
use App\Infrastructure\Persist\Gallery\GalleryRepository;
use App\Tests\Application\HandlerTestCase;
use App\Tests\Infrastructure\Projection\Gallery\InMemoryGalleryProjector;
use N3ttech\Messaging\Aggregate\AggregateType;
use N3ttech\Messaging\Aggregate\EventBridge\AggregateChanged;
use N3ttech\Valuing as VO;

/**
 * @internal
 * @coversNothing
 */
class CreateGalleryHandlerTest extends HandlerTestCase
{
    /** @var Service\GalleryCommandManager */
    private $command;

    public function setUp(): void
    {
        $repository = new GalleryRepository($this->getEventStorage(), $this->getSnapshotStorage());

        $this->register(Projection\GalleryProjector::class, new InMemoryGalleryProjector());
        $this->register(Command\CreateGalleryHandler::class, new Command\CreateGalleryHandler($repository));

        $this->command = new Service\GalleryCommandManager($this->getCommandBus());
    }

    /**
     * @test
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function itCreatesNewGalleryTest(): void
    {
        //given
        $uuid = VO\Identity\Uuid::fromIdentity(\Ramsey\Uuid\Uuid::uuid4()->toString());
        $name = VO\Char\Text::fromString('name');
        $source = VO\Char\Text::fromString('source');
        $assets = Gallery\Assets::fromArray(['asset.jpg']);

        //when
        $this->command->create($uuid->toString(), $name->toString(), $source->toString(), $assets->toArray());

        //then
        /** @var InMemoryGalleryProjector $projector */
        $projector = $this->container->get(Projection\GalleryProjector::class);
        $entity = $projector->get($uuid->toString());

        $this->assertEquals($entity->identifier(), $uuid->toString());
        $this->assertEquals($entity->getName(), $name->toString());
        $this->assertEquals($entity->getSource(), $source->toString());

        $collection = $this->getStreamRepository()->load($uuid, 1);

        foreach ($collection->getArrayCopy() as $eventStream) {
            $event = $eventStream->getEventName();
            /** @var AggregateChanged $event */

            /** @var Event\NewGalleryCreated $event */
            $event = $event::fromEventStream($eventStream);

            $this->assertEquals($entity->identifier(), $event->galleryUuid()->toString());
            $this->assertEquals($entity->getName(), $event->galleryName()->toString());
            $this->assertEquals($entity->getSource(), $event->gallerySource()->toString());
        }

        $snapshot = $this->getSnapshotRepository()->get(AggregateType::fromAggregateRootClass(Gallery::class), $uuid);
        $this->assertEquals($snapshot->getLastVersion(), 1);
    }
}
