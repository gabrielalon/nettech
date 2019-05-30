<?php

namespace App\Tests\Application\MessageHandler\Query;

use App\Application\Message\PurchaseTransaction\Query\FindTransaction;
use App\Application\Message\PurchaseTransaction\Reply\Transaction;
use App\Application\Model\PurchaseTransaction;
use App\Domain\Transaction\PurchaseTransaction\Command\CreateTransaction;
use App\Domain\User\Command\CreateUser;
use App\Infrastructure\Common\HandleTrait;
use App\Infrastructure\Transaction\NumberGenerator\GeneratorInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FindTransactionHandlerTest extends KernelTestCase
{
    use HandleTrait;

    protected function setUp(): void
    {
        static::bootKernel();
    }

    public function testFindExistingTransaction(): void
    {
        $userId = Uuid::uuid4()->toString();
        $transactionId = Uuid::uuid4()->toString();
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

        $queryBus = static::$container->get('messenger.bus.app.queries');

        /** @var PurchaseTransaction $transaction */
        $transaction = $this->handle($queryBus, new FindTransaction($transactionId));

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertSame(new Transaction(
            $transactionId,
            'PLN'
        ), $transaction);
    }

    public function testFindNotExistingTransaction(): void
    {
        $queryBus = static::$container->get('messenger.bus.app.queries');
        $transaction = $this->handle($queryBus, new FindTransaction(Uuid::uuid4()->toString()));

        $this->assertNull($transaction);
    }
}
