<?php

namespace App\Infrastructure\EventSourcing\Event;

final class DomainMessage
{
    /**
     * @var string
     */
    private $id;

    /**
     * @var int
     */
    private $playHead;

    /**
     * @var object
     */
    private $payload;

    /**
     * @var \DateTimeImmutable
     */
    private $recordedOn;

    public function __construct(string $id, int $playHead, object $payload, \DateTimeImmutable $recordedOn)
    {
        $this->id = $id;
        $this->playHead = $playHead;
        $this->payload = $payload;
        $this->recordedOn = $recordedOn;
    }

    public static function recordNow(string $id, int $playHead, object $payload): self
    {
        return new self($id, $playHead, $payload, new \DateTimeImmutable('@' . time()));
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getPlayHead(): int
    {
        return $this->playHead;
    }

    public function getPayload(): object
    {
        return $this->payload;
    }

    public function getRecordedOn(): \DateTimeImmutable
    {
        return $this->recordedOn;
    }

    public function getType(): string
    {
        return \get_class($this->payload);
    }
}
