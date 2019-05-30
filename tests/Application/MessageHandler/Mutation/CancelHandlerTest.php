<?php

namespace App\Tests\Application\MessageHandler\Mutation;

use App\Application\Message\PurchaseTransaction\Mutation\Cancel;
use App\Application\Message\PurchaseTransaction\Reply\Transaction;
use App\Application\Message\PurchaseTransaction\Subscription\Canceled;
use App\Domain\Transaction\PurchaseTransaction\Command\CreateTransaction;
use App\Domain\User\Command\CreateUser;
use App\Infrastructure\Common\HandleTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\TraceableMessageBus;

class CancelHandlerTest extends KernelTestCase
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

    public function testCancel(): void
    {
        $userId = Uuid::uuid4()->toString();
        $transactionId = Uuid::uuid4()->toString();

        $this->commandBus->dispatch(new CreateUser(
            $userId,
            uniqid(),
            uniqid(),
            uniqid(),
            uniqid(),
            null
        ));
        $this->commandBus->dispatch(new CreateTransaction(
            $transactionId,
            $userId,
            uniqid(),
            'PLN'
        ));

        $this->subscriptionBus->reset();

        $transaction = $this->handle($this->mutationBus, new Cancel($transactionId, $userId));
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertSame(new Transaction($transactionId, 'PLN'), $transaction);

        $subscriptions = $this->subscriptionBus->getDispatchedMessages();
        $this->assertCount(1, $subscriptions);
        $this->assertInstanceOf(Canceled::class, $subscriptions[0]['message']);
        $this->assertSame(new Canceled($transactionId, 'PLN'), $subscriptions[0]['message']);
    }
}
