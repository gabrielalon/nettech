<?php

namespace App\Tests\Application\MessageHandler\Mutation;

use App\Application\Message\Common\ConstraintViolations;
use App\Application\Message\PurchaseTransaction\Mutation\CreateTransaction;
use App\Application\Message\PurchaseTransaction\Reply\Transaction;
use App\Application\Message\PurchaseTransaction\Subscription\Created;
use App\Application\Model;
use App\Domain\User\Command\CreateUser;
use App\Infrastructure\Common\HandleTrait;
use App\Infrastructure\Messenger\Stamp\CorrelationStamp;
use App\Infrastructure\Messenger\Stamp\ReplyToStamp;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\TraceableMessageBus;
use Symfony\Component\Validator\Constraints\Length;

class CreateTransactionHandlerTest extends KernelTestCase
{
    use HandleTrait;

    protected function setUp(): void
    {
        static::bootKernel();
    }

    public function testCreateTransaction(): void
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

        /** @var Model\PurchaseTransaction $transaction */
        $transaction = $this->handle($mutationBus, new Envelope(
            new CreateTransaction(
                $userId,
                'PLN'
            ),
            new ReplyToStamp(Uuid::uuid4()->toString()),
            new CorrelationStamp(Uuid::uuid4()->toString())
        ));

        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertSame('PLN', $transaction->getCurrency());

        /** @var TraceableMessageBus $subscriptionBus */
        $subscriptionBus = static::$container->get('messenger.bus.app.subscriptions');
        $subscriptions = $subscriptionBus->getDispatchedMessages();

        $this->assertCount(1, $subscriptions);
        $this->assertInstanceOf(Created::class, $subscriptions[0]['message']);
        $this->assertSame(new Created(
            $transaction->getId(),
            'PLN'
        ), $subscriptions[0]['message']);
    }

    /**
     * @dataProvider invalidCurrencyProvider
     */
    public function testInvalidCurrency(string $currency, string $code): void
    {
        $mutationBus = static::$container->get('messenger.bus.app.mutations');

        /** @var ConstraintViolations $constraintViolations */
        $constraintViolations = $this->handle($mutationBus, new Envelope(
            new CreateTransaction(
                Uuid::uuid4()->toString(),
                $currency
            ),
            new ReplyToStamp(Uuid::uuid4()->toString()),
            new CorrelationStamp(Uuid::uuid4()->toString())
        ));

        $this->assertInstanceOf(ConstraintViolations::class, $constraintViolations);
        $this->assertSame(new ConstraintViolations(new Model\ConstraintViolations([
            new Model\ConstraintViolation('currency', 'This value should have exactly 3 characters.'),
        ])), $constraintViolations);
    }

    public function invalidCurrencyProvider(): array
    {
        return [
            ['AB', Length::TOO_SHORT_ERROR],
            ['ABCD', Length::TOO_LONG_ERROR],
        ];
    }
}
