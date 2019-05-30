<?php

namespace App\Tests\Application\MessageHandler\Query;

use App\Application\Message\PurchaseTransaction\Query\FindTransactionItems;
use App\Application\Message\PurchaseTransaction\Reply\Items;
use App\Application\Model\Price;
use App\Application\Model\PurchaseTransaction\Item;
use App\Domain\Transaction\PurchaseTransaction\Command\AddItem;
use App\Domain\Transaction\PurchaseTransaction\Command\CreateTransaction;
use App\Domain\Transaction\PurchaseTransaction\Dto\PriceDto;
use App\Domain\User\Command\CreateUser;
use App\Infrastructure\Common\HandleTrait;
use App\Infrastructure\Testing\Constraint\InArray;
use App\Infrastructure\Transaction\NumberGenerator\GeneratorInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindTransactionItemsHandlerTest extends KernelTestCase
{
    use HandleTrait;

    protected function setUp(): void
    {
        static::bootKernel();
    }

    public function testFindExistingItems(): void
    {
        $userId = Uuid::uuid4()->toString();
        $transactionId = Uuid::uuid4()->toString();
        $itemIds = [
            Uuid::uuid4()->toString(),
            Uuid::uuid4()->toString(),
        ];
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
            $itemIds[0],
            $transactionId,
            $userId,
            '#qw-erty',
            'example-item-1',
            'xs',
            2,
            new PriceDto([
                'nett'         => 10000,
                'gross'        => 12300,
                'taxRate'      => 23,
                'currencyCode' => 'PLN',
            ])
        ));
        $commandBus->dispatch(new AddItem(
            $itemIds[1],
            $transactionId,
            $userId,
            '#po-iuyt',
            'example-item-2',
            'xl',
            8,
            new PriceDto([
                'nett'         => 10000,
                'gross'        => 12300,
                'taxRate'      => 23,
                'currencyCode' => 'PLN',
            ])
        ));

        $queryBus = static::$container->get('messenger.bus.app.queries');

        /** @var Items $items */
        $items = $this->handle($queryBus, new FindTransactionItems($transactionId));

        $this->assertCount(2, $items);
        $this->assertInstanceOf(Items::class, $items);
        $this->assertThat($items, $this->containsOnlyInstancesOf(Item::class));
        $this->assertThat(new Item(
            $itemIds[0],
            $transactionId,
            '#qw-erty',
            'example-item-1',
            'xs',
            2,
            new Price(
                10000,
                12300,
                23,
                'PLN'
            )
        ), new InArray($items));
        $this->assertThat(new Item(
            $itemIds[1],
            $transactionId,
            '#po-iuyt',
            'example-item-2',
            'xl',
            8,
            new Price(
                10000,
                12300,
                23,
                'PLN'
            )
        ), new InArray($items));
    }
}
