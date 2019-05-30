<?php

namespace App\Infrastructure\Common;

use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

trait HandleTrait
{
    private function handle(MessageBusInterface $messageBus, object $message)
    {
        if (!$messageBus instanceof MessageBusInterface) {
            throw new \LogicException(
                sprintf(
                'You must provide a "%s" instance in the "%s::$messageBus" property, "%s" given.',
                MessageBusInterface::class,
                \get_class($this),
                \is_object($messageBus) ? \get_class($messageBus) : \gettype($messageBus)
                )
            );
        }

        $envelope = $messageBus->dispatch($message);
        /** @var HandledStamp[] $handledStamps */
        $handledStamps = $envelope->all(HandledStamp::class);

        if (!$handledStamps) {
            throw new \LogicException(
                sprintf(
                'Message of type "%s" was handled zero times. 
                    Exactly one handler is expected when using "%s::%s()".',
                \get_class($envelope->getMessage()),
                \get_class($this),
                __FUNCTION__
                )
            );
        }

        if (\count($handledStamps) > 1) {
            $handlers = implode(
                ', ',
                array_map(
                    function (HandledStamp $stamp): string {
                        return sprintf('"%s"', $stamp->getHandlerAlias() ?? $stamp->getCallableName());
                    },
                    $handledStamps
                )
            );

            throw new \LogicException(
                sprintf(
                'Message of type "%s" was handled multiple times. 
                    Only one handler is expected when using "%s::%s()", got %d: %s.',
                \get_class($envelope->getMessage()),
                \get_class($this),
                __FUNCTION__,
                \count($handledStamps),
                $handlers
                )
            );
        }

        return $handledStamps[0]->getResult();
    }
}
