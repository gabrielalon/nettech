<?php

namespace App\Tests\Application\MessageHandler\Mutation;

use App\Application\Message\PurchaseTransaction\Mutation\AddItem;
use App\Application\Message\PurchaseTransaction\Mutation\CreateTransaction;
use App\Application\Message\PurchaseTransaction\Reply\Item;
use App\Application\Message\PurchaseTransaction\Subscription\ItemAdded;
use App\Application\Model;
use App\Domain\User\Command\CreateUser;
use App\Infrastructure\Common\HandleTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\TraceableMessageBus;
use Symfony\Component\Serializer\SerializerInterface;

class AddItemHandlerTest extends KernelTestCase
{
    use HandleTrait;

    protected function setUp(): void
    {
        static::bootKernel();
    }

    public function testAddItem(): void
    {
        $userId = Uuid::uuid4()->toString();

        $commandBus = static::$container->get('messenger.bus.domain.commands');
        $commandBus->dispatch(new CreateUser(
            $userId,
            uniqid(),
            uniqid(),
            uniqid(),
            uniqid(),
            null
        ));

        $mutationBus = static::$container->get('messenger.bus.app.mutations');

        /** @var Model\PurchaseTransaction $purchaseTransaction */
        $purchaseTransaction = $this->handle($mutationBus, new CreateTransaction(
            $userId,
            'PLN'
        ));

        /** @var TraceableMessageBus $subscriptionBus */
        $subscriptionBus = static::$container->get('messenger.bus.app.subscriptions');
        $subscriptionBus->reset();

        /** @var Item $item */
        $result = $this->handle($mutationBus, new AddItem(
            $purchaseTransaction->getId(),
            $userId,
            '#ab-cde',
            'example-product',
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

        $this->assertArraySubset([
            'transactionId' => $purchaseTransaction->getId(),
            'sku'           => '#ab-cde',
            'name'          => 'example-product',
            'option'        => 'xs',
            'quantity'      => 3,
            'price'         => [
                'nett'     => 10000,
                'gross'    => 12300,
                'tax'      => 23,
                'currency' => 'PLN',
            ],
        ], $item);

        $subscriptions = $subscriptionBus->getDispatchedMessages();

        $this->assertCount(1, $subscriptions);
        $this->assertInstanceOf(ItemAdded::class, $subscriptions[0]['message']);
        $this->assertSame(new ItemAdded(
            $item['id'],
            $purchaseTransaction->getId(),
            '#ab-cde',
            'example-product',
            'xs',
            3,
            new Model\Price(10000, 12300, 23, 'PLN')
        ), $subscriptions[0]['message']);
    }
}
