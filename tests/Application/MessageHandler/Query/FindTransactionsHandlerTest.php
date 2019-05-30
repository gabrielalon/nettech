<?php

namespace App\Tests\Application\MessageHandler\Query;

use App\Application\Message\PurchaseTransaction\Query\FindTransactions;
use App\Application\Message\PurchaseTransaction\Reply\Transactions;
use App\Application\Model\PurchaseTransaction;
use App\Application\Model\PurchaseTransactions;
use App\Domain\Transaction\PurchaseTransaction\Command\CreateTransaction;
use App\Domain\User\Command\CreateUser;
use App\Infrastructure\Common\HandleTrait;
use App\Infrastructure\Testing\Constraint\InArray;
use App\Infrastructure\Transaction\NumberGenerator\GeneratorInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindTransactionsHandlerTest extends KernelTestCase
{
    use HandleTrait;

    protected function setUp(): void
    {
        static::bootKernel();
    }

    /**
     * @resetDatabase
     */
    public function testFindExistingTransactions(): void
    {
        $userId = Uuid::uuid4()->toString();
        $transactionIds = [
            Uuid::uuid4()->toString(),
            Uuid::uuid4()->toString(),
        ];

        /** @var GeneratorInterface $symbolGenerator */
        $symbolGenerator = static::$container->get(GeneratorInterface::class);
        $numbers = [];

        $commandBus = static::$container->get('messenger.bus.domain.commands');
        $commandBus->dispatch(new CreateUser(
            $userId,
            uniqid(),
            uniqid(),
            uniqid(),
            uniqid(),
            null
        ));

        $numbers[] = $symbolGenerator->generate();
        $commandBus->dispatch(new CreateTransaction($transactionIds[0], $userId, end($numbers), 'PLN'));

        $numbers[] = $symbolGenerator->generate();
        $commandBus->dispatch(new CreateTransaction($transactionIds[1], $userId, end($numbers), 'GBP'));

        $queryBus = static::$container->get('messenger.bus.app.queries');
        $transactions = $this->handle($queryBus, new FindTransactions());

        $this->assertCount(2, $transactions);
        $this->assertInstanceOf(Transactions::class, $transactions);
        $this->assertThat($transactions, $this->containsOnlyInstancesOf(PurchaseTransaction::class));
        $this->assertThat(new PurchaseTransaction($transactionIds[0], 'PLN'), new InArray($transactions));
        $this->assertThat(new PurchaseTransaction($transactionIds[1], 'GBP'), new InArray($transactions));
    }

    /**
     * @resetDatabase
     */
    public function testFindNotExistingTransactions(): void
    {
        $queryBus = static::$container->get('messenger.bus.app.queries');

        /** @var PurchaseTransactions $transactions */
        $transactions = $this->handle($queryBus, new FindTransactions());

        $this->assertCount(0, iterator_to_array($transactions->getIterator()));
    }
}
