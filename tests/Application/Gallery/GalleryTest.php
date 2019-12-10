<?php

namespace App\Tests\Application\Gallery;

use App\Application\Gallery\Event;
use App\Domain\Model\Gallery\Gallery;
use App\Tests\Application\AggregateChangedTestCase;
use N3ttech\Messaging\Aggregate\AggregateRoot;
use N3ttech\Messaging\Aggregate\EventBridge\AggregateChanged;
use N3ttech\Valuing as VO;

/**
 * @internal
 * @coversNothing
 */
class GalleryTest extends AggregateChangedTestCase
{
    /** @var VO\Identity\Uuid */
    private $uuid;

    /** @var VO\Char\Text */
    private $name;

    /** @var VO\Char\Text */
    private $source;

    /** @var Gallery\Assets */
    private $assets;

    /**
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    protected function setUp(): void
    {
        $this->uuid = VO\Identity\Uuid::fromIdentity(\Ramsey\Uuid\Uuid::uuid4()->toString());
        $this->name = VO\Char\Text::fromString('name');
        $this->source = VO\Char\Text::fromString('source');
        $this->assets = Gallery\Assets::fromArray(['asset.jpg']);
    }

    /**
     * @test
     *
     * @throws \Assert\AssertionFailedException
     */
    public function itCreatesNewGalleryTest(): void
    {
        $gallery = Gallery::createNewGallery($this->uuid, $this->name, $this->source, $this->assets);

        /** @var AggregateChanged[] $events */
        $events = $this->popRecordedEvents($gallery);

        $this->assertCount(1, $events);

        /** @var Event\NewGalleryCreated $event */
        $event = $events[0];

        $this->assertSame(Event\NewGalleryCreated::class, $event->messageName());
        $this->assertTrue($this->uuid->equals($event->galleryUuid()));
        $this->assertTrue($this->name->equals($event->galleryName()));
        $this->assertTrue($this->source->equals($event->gallerySource()));
        $this->assertTrue($this->assets->equals($event->galleryAssets()));
    }

    /**
     * @param AggregateChanged ...$events
     *
     * @return AggregateRoot
     */
    private function reconstituteReturnPackageFromHistory(AggregateChanged ...$events): AggregateRoot
    {
        return $this->reconstituteAggregateFromHistory(
            Gallery::class,
            $events
        );
    }

    /**
     * @return AggregateChanged
     */
    private function newGalleryCreated(): AggregateChanged
    {
        return Event\NewGalleryCreated::occur($this->uuid->toString(), [
            'creation_date' => time(),
            'name' => $this->name->toString(),
            'source' => $this->source->toString(),
            'assets' => $this->assets->toArray(),
        ]);
    }
}
