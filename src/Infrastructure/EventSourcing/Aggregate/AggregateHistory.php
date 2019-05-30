<?php

declare(strict_types=1);

namespace App\Infrastructure\EventSourcing\Aggregate;

use App\Infrastructure\EventSourcing\Aggregate\Exception\CorruptAggregateHistoryException;
use App\Infrastructure\EventSourcing\Event\DomainMessage;
use App\Infrastructure\EventSourcing\Event\DomainMessagesStream;
use App\Infrastructure\EventSourcing\Shared\IdentifyInterface;

final class AggregateHistory extends DomainMessagesStream
{
    private $aggregateId;

    /**
     * @throws CorruptAggregateHistoryException
     */
    public function __construct(IdentifyInterface $aggregateId, array $domainMessages)
    {
        /** @var $domainMessage DomainMessage */
        foreach ($domainMessages as $domainMessage) {
            if ($domainMessage->getId() !== $aggregateId->__toString()) {
                throw new CorruptAggregateHistoryException(
                    sprintf(
                        'Event with invalid aggregate root id (%s) detected',
                        $domainMessage->getId()
                    )
                );
            }
        }

        parent::__construct($domainMessages);
        $this->aggregateId = $aggregateId;
    }

    public function getAggregateId(): IdentifyInterface
    {
        return $this->aggregateId;
    }
}
