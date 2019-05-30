<?php

namespace App\Tests\Application\MessageHandler\Mutation;

use App\Application\Message\PurchaseTransaction\Mutation\AddItem;
use App\Application\Message\PurchaseTransaction\Mutation\CreateTransaction;
use App\Application\Message\PurchaseTransaction\Mutation\RemoveItem;
use App\Application\Message\PurchaseTransaction\Reply\Item;
use App\Application\Message\PurchaseTransaction\Subscription\ItemRemoved;
use App\Application\Model;
use App\Domain\User\Command\CreateUser;
use App\Infrastructure\Common\HandleTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;
use Symfony\Component\Serializer\SerializerInterface;

class RemoveItemHandlerTest extends KernelTestCase
{
    use HandleTrait;

    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @var MessageBusInterface
     */
    private $mutationBus;

    /**
     * @var TraceableMessageBus
     */
    private $subscriptionBus;

    protected function setUp(): void
    {
        static::bootKernel();

        $this->commandBus = static::$container->get('messenger.bus.domain.commands');
        $this->mutationBus = static::$container->get('messenger.bus.app.mutations');
        $this->subscriptionBus = static::$container->get('messenger.bus.app.subscriptions');
    }

    public function testRemoveItem(): void
    {
        $userId = Uuid::uuid4()->toString();

        $this->commandBus->dispatch(new CreateUser(
            $userId,
            uniqid(),
            uniqid(),
            uniqid(),
            uniqid(),
            null
        ));

        /** @var Model\PurchaseTransaction $purchaseTransaction */
        $purchaseTransaction = $this->handle($this->mutationBus, new CreateTransaction(
            $userId,
            'PLN'
        ));

        /** @var Item $item */
        $result = $this->handle($this->mutationBus, new AddItem(
            $purchaseTransaction->getId(),
            $userId,
            '#ab-cde',
            'example-item',
            'xs',
            3,
            [
                'nett'         => 10000,
                'gross'        => 12300,
                'taxRate'      => 23,
                'currencyCode' => 'PLN',
            ]
        ));
        $item = $result->normalize(static::$container->get(SerializerInterface::class));

        $this->subscriptionBus->reset();

        /** @var Item $removeItemResult */
        $removeItemResult = $this->handle($this->mutationBus, new RemoveItem(
            $purchaseTransaction->getId(),
            $item['id'],
            $userId
        ));

        /** @var Model\PurchaseTransaction\Item $removedItem */
        $removedItem = $removeItemResult->normalize(static::$container->get(SerializerInterface::class));

        $this->assertInstanceOf(Item::class, $removeItemResult);
        $this->assertSame($item, $removedItem);

        $subscriptions = $this->subscriptionBus->getDispatchedMessages();

        $this->assertCount(1, $subscriptions);
        $this->assertInstanceOf(ItemRemoved::class, $subscriptions[0]['message']);
        $this->assertSame(new ItemRemoved(
            $item['id'],
            $purchaseTransaction->getId(),
            '#ab-cde',
            'example-item',
            'xs',
            3,
            new Model\Price(10000, 12300, 23, 'PLN')
        ), $subscriptions[0]['message']);
    }
}
