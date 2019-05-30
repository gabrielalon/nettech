<?php

namespace App\Tests\Application\MessageHandler\Query;

use App\Application\Message\PurchaseTransaction\Query\FindTransactionItem;
use App\Application\Message\PurchaseTransaction\Reply\Item;
use App\Domain\Transaction\PurchaseTransaction\Command\AddItem;
use App\Domain\Transaction\PurchaseTransaction\Command\CreateTransaction;
use App\Domain\Transaction\PurchaseTransaction\Dto\PriceDto;
use App\Domain\User\Command\CreateUser;
use App\Infrastructure\Common\HandleTrait;
use App\Infrastructure\Transaction\NumberGenerator\GeneratorInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\SerializerInterface;

class FindTransactionItemHandlerTest extends KernelTestCase
{
    use HandleTrait;

    protected function setUp(): void
    {
        static::bootKernel();
    }

    public function testFindExistingItem(): void
    {
        $userId = Uuid::uuid4()->toString();
        $transactionId = Uuid::uuid4()->toString();
        $itemId = Uuid::uuid4()->toString();
        $symbolGenerator = static::$container->get(GeneratorInterface::class);
        $symbol = $symbolGenerator->generate();

        $commandBus = static::$container->get('messenger.bus.domain.commands');
        $commandBus->dispatch(new CreateUser(
            $userId,
            uniqid(),
            uniqid(),
            uniqid(),
            uniqid(),
            null
        ));
        $commandBus->dispatch(new CreateTransaction($transactionId, $userId, $symbol, 'PLN'));
        $commandBus->dispatch(new AddItem(
            $itemId,
            $transactionId,
            $userId,
            '#ab-cde',
            'example-item',
            'xs',
            4,
            new PriceDto([
                'nett'         => 10000,
                'gross'        => 12300,
                'taxRate'      => 23,
                'currencyCode' => 'PLN',
            ])
        ));

        $queryBus = static::$container->get('messenger.bus.app.queries');

        /** @var Item $result */
        $result = $this->handle($queryBus, new FindTransactionItem($itemId));
        $item = $result->normalize(static::$container->get(SerializerInterface::class));

        $this->assertSame([
            'id'            => $itemId,
            'transactionId' => $transactionId,
            'sku'           => '#ab-cde',
            'name'          => 'example-item',
            'option'        => 'xs',
            'quantity'      => 4,
            'price'         => [
                'nett'     => 10000,
                'gross'    => 12300,
                'tax'      => 23,
                'currency' => 'PLN',
            ],
        ], $item);
    }

    public function testFindNotExistingItem(): void
    {
        $queryBus = static::$container->get('messenger.bus.app.queries');
        $item = $this->handle($queryBus, new FindTransactionItem(Uuid::uuid4()->toString()));

        $this->assertNull($item);
    }
}
