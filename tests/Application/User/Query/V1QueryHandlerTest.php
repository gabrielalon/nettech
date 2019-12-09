<?php

namespace App\Tests\Application\User\Query;

use App\Application\User\Query;
use App\Application\User\Service;
use App\Tests\Application\HandlerTestCase;
use App\Tests\Infrastructure\Query\User\InMemoryUserQuery;
use Ramsey\Uuid\Uuid;

/**
 * @internal
 * @coversNothing
 */
class V1QueryHandlerTest extends HandlerTestCase
{
    /** @var Query\ReadModel\Entity\User */
    private $user;

    /** @var Service\UserQueryManager */
    private $userQuery;

    /**
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function setUp(): void
    {
        $this->user = new Query\ReadModel\Entity\User(
            Uuid::uuid4()->toString(),
            'admin', 'admin',
            \DateTimeImmutable::createFromMutable(date_create())
        );

        $collection = new Query\ReadModel\Entity\UserCollection();
        $collection->add($this->user);

        $userQuery = new InMemoryUserQuery($collection);
        $this->register(Query\V1\FindOneUserByLoginHandler::class, new Query\V1\FindOneUserByLoginHandler($userQuery));

        $this->userQuery = new Service\UserQueryManager($this->getQueryBus());
    }

    /**
     * @test
     */
    public function itFindsUserByUuid(): void
    {
        $user = $this->userQuery->findOneByLogin($this->user->getLogin());

        $this->assertEquals($user->getLogin(), $this->user->getLogin());
    }

    public function itFindsAllUsers(): void
    {
        $users = $this->userQuery->findAll();

        $this->assertEquals($users->count(), 1);
    }
}
