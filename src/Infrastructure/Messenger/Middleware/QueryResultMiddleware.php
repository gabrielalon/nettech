<?php

namespace App\Infrastructure\Messenger\Middleware;

use App\Infrastructure\Messenger\Message\ResultInterface;
use App\Infrastructure\Messenger\Stamp\CorrelationStamp;
use App\Infrastructure\Messenger\Stamp\ReplyToStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\SentStamp;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;

class QueryResultMiddleware implements MiddlewareInterface
{
    /**
     * @var SendersLocatorInterface
     */
    private $sendersLocator;

    public function __construct(SendersLocatorInterface $sendersLocator)
    {
        $this->sendersLocator = $sendersLocator;
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if (empty($envelope->all(HandledStamp::class))) {
            return $envelope;
        }

        $handle = false;
        $sender = null;

        /** @var HandledStamp $handledStamp */
        $handledStamp = $envelope->last(HandledStamp::class);

        $result = $handledStamp->getResult();
        if (!\is_object($result)) {
            throw new \RuntimeException('Result must be an object');
        }

        if (!($result instanceof ResultInterface)) {
            throw new \RuntimeException(sprintf(
                'Result %s must be type of %s',
                \get_class($result),
                ResultInterface::class
            ));
        }

        $replyToEnvelope = (new Envelope($result))
            ->with($envelope->last(ReplyToStamp::class))
            ->with($envelope->last(CorrelationStamp::class));

        foreach ($this->sendersLocator->getSenders($replyToEnvelope, $handle) as $alias => $sender) {
            $sentEnvelope = $sender
                ->send($replyToEnvelope)
                ->with(new SentStamp(\get_class($sender), \is_string($alias) ? $alias : null));
        }

        if (null === $sender || $handle) {
            return $stack->next()->handle($sentEnvelope, $stack);
        }

        return $sentEnvelope;
    }
}
