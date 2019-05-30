<?php

namespace App\Tests\Application\MessageHandler\Auth\Query;

use App\Application\Message\Auth\Query\FindUsers;
use App\Application\Message\Auth\Reply\Users;
use App\Application\Model\User;
use App\Domain\User\Command\CreateUser;
use App\Domain\User\Service\PasswordEncoderService;
use App\Infrastructure\Common\HandleTrait;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Messenger\MessageBusInterface;

class FindUsersHandlerTest extends KernelTestCase
{
    use HandleTrait;

    protected function setUp(): void
    {
        static::bootKernel();
    }

    /**
     * @resetDatabase
     */
    public function testFindAllUsers(): void
    {
        $userIds = [
            Uuid::uuid4()->toString(),
            Uuid::uuid4()->toString(),
            Uuid::uuid4()->toString(),
        ];
        $passwords = [
            uniqid(),
            uniqid(),
            uniqid(),
        ];

        $commandBus = static::$container->get('messenger.bus.domain.commands');
        $commandBus->dispatch(new CreateUser(
            $userIds[0],
            uniqid(),
            uniqid(),
            'FirstUsername',
            $passwords[0],
            null
        ));
        $commandBus->dispatch(new CreateUser(
            $userIds[1],
            uniqid(),
            uniqid(),
            'SecondUsername',
            $passwords[1],
            null
        ));
        $commandBus->dispatch(new CreateUser(
            $userIds[2],
            uniqid(),
            uniqid(),
            'ThirdUsername',
            $passwords[2],
            null
        ));

        /** @var MessageBusInterface $queryBus */
        $queryBus = static::$container->get('messenger.bus.app.queries');

        /** @var Users $users */
        $users = $this->handle($queryBus, new FindUsers());

        $this->assertCount(3, $users);
        $this->assertInstanceOf(Users::class, $users);
        $this->assertThat($users, $this->containsOnlyInstancesOf(User::class));

        $this->assertUser($userIds[0], 'FirstUsername', $users);
        $this->assertUser($userIds[1], 'SecondUsername', $users);
        $this->assertUser($userIds[2], 'ThirdUsername', $users);
    }

    /**
     * @resetDatabase
     */
    public function testFindUserByUsername(): void
    {
        $userId = Uuid::uuid4()->toString();
        $password = uniqid();

        $commandBus = static::$container->get('messenger.bus.domain.commands');
        $commandBus->dispatch(new CreateUser(
            Uuid::uuid4()->toString(),
            uniqid(),
            uniqid(),
            uniqid(),
            uniqid(),
            null
        ));
        $commandBus->dispatch(new CreateUser(
            $userId,
            uniqid(),
            uniqid(),
            'JanJanowski',
            $password,
            null
        ));
        $commandBus->dispatch(new CreateUser(
            Uuid::uuid4()->toString(),
            uniqid(),
            uniqid(),
            uniqid(),
            uniqid(),
            null
        ));

        /** @var MessageBusInterface $queryBus */
        $queryBus = static::$container->get('messenger.bus.app.queries');

        static::$container->get(PasswordEncoderService::class);

        /** @var Users $users */
        $users = $this->handle($queryBus, new FindUsers((object) [
            'username' => [
                'equals' => 'JanJanowski',
            ],
        ]));

        $this->assertCount(1, $users);
        $this->assertInstanceOf(Users::class, $users);
        $this->assertThat($users, $this->containsOnlyInstancesOf(User::class));
        $this->assertUser($userId, 'JanJanowski', $users);
    }

    private function assertUser(string $id, string $username, $users): void
    {
        /** @var User $user */
        foreach ($users as $user) {
            if ($user->getId() === $id) {
                $this->assertSame($id, $user->getId());
                $this->assertSame($username, $user->getUsername());
                $this->assertNotEmpty($user->getPassword());

                return;
            }
        }

        throw new \Exception(sprintf('User with id %s not found', $id));
    }
}
