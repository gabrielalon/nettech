<?php

namespace App\Domain\User\Testing;

use App\Domain\User\Command\CreateUser;
use App\Domain\User\Command\RemoveUserCommand;
use App\Domain\User\Command\UpdateUserCommand;
use App\Domain\User\Query\FindUser;
use App\Domain\User\ReadModel\Entity\User;
use App\Infrastructure\Common\HandleTrait;
use App\Infrastructure\Testing\AbstractHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserHelper extends AbstractHelper
{
    use HandleTrait;

    /**
     * @var TestCase
     */
    private $testCase;

    /**
     * @var MessageBusInterface
     */
    private $commandBus;

    /**
     * @var MessageBusInterface
     */
    private $queryBus;

    public function __construct(TestCase $testCase, MessageBusInterface $commandBus, MessageBusInterface $queryBus)
    {
        $this->testCase = $testCase;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
    }

    public function create(array $data)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['id']);
        $resolver->setDefaults(
            [
                'firstName' => 'moje-imie-cc',
                'lastName'  => 'romeczkowskii',
                'login'     => 'romeczkowskii',
                'password'  => 'romeczkowskii',
                'roleId'    => null,
            ]
        );
        $data = $resolver->resolve($data);

        $this->commandBus->dispatch(
            new CreateUser(
                $data['id'],
                $data['firstName'],
                $data['lastName'],
                $data['login'],
                $data['password'],
                $data['roleId']
            )
        );
    }

    public function changeUserData(array $data)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['id', 'firstName', 'lastName', 'login', 'password']);
        $data = $resolver->resolve($data);

        $this->commandBus->dispatch(
            new UpdateUserCommand(
                $data['id'],
                $data['firstName'],
                $data['lastName'],
                $data['login'],
                $data['password']
            )
        );
    }

    public function remove(array $data)
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['id']);
        $data = $resolver->resolve($data);

        $this->commandBus->dispatch(
            new RemoveUserCommand(
            $data['id']
            )
        );
    }

    public function assertCreate(string $id, array $data): void
    {
        /** @var User $user */
        $user = $this->handle($this->queryBus, new FindUser($id));

        $this->runAssertions(
            [
            'id'        => function ($expected, User $user) {
                $this->testCase->assertEquals($expected, $user->getId());
            },
            'firstName' => function ($expected, User $user) {
                $this->testCase->assertEquals($expected, $user->getFirstName());
            },
            'lastName'  => function ($expected, User $user) {
                $this->testCase->assertEquals($expected, $user->getLastName());
            },
            'login'     => function ($expected, User $user) {
                $this->testCase->assertEquals($expected, $user->getLogin());
            },
            'roleId'    => function ($expected, User $user) {
                $this->testCase->assertEquals($expected, $user->getRole());
            },
        ],
            $user,
            $data
        );
    }

    public function assertUpdateData(string $id, array $data): void
    {
        /** @var User $user */
        $user = $this->handle($this->queryBus, new FindUser($id));

        $this->runAssertions(
            [
            'id'        => function ($expected, User $user) {
                $this->testCase->assertEquals($expected, $user->getId());
            },
            'firstName' => function ($expected, User $user) {
                $this->testCase->assertEquals($expected, $user->getFirstName());
            },
            'lastName'  => function ($expected, User $user) {
                $this->testCase->assertEquals($expected, $user->getLastName());
            },
            'login'     => function ($expected, User $user) {
                $this->testCase->assertEquals($expected, $user->getLogin());
            },
        ],
            $user,
            $data
        );
    }

    public function assertRemove(string $id)
    {
        /** @var User $user */
        $user = $this->handle($this->queryBus, new FindUser($id));

        $this->testCase->assertNull($user);
    }
}
