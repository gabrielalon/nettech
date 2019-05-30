<?php

namespace App\Tests\Integration;

use App\Domain\User\Exception\UserDoesNotExistException;
use App\Domain\User\Testing\UserHelper;
use App\Infrastructure\Testing\DomainTestCase;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class UserTest extends DomainTestCase
{
    /**
     * @var UserHelper
     */
    private $userHelper;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userHelper = new UserHelper(
            $this,
            $this->domainCommandBus,
            $this->domainQueryBus
        );
    }

    public function testCreate(): UuidInterface
    {
        $userId = Uuid::uuid4();

        $this->userHelper->create([
            'id'        => $userId,
            'firstName' => 'Wawid',
            'lastName'  => 'Wawidowski',
            'login'     => 'dadamczyk',
            'roleId'    => null,
        ]);

        $this->userHelper->assertCreate($userId, [
            'id'        => $userId,
            'firstName' => 'Wawid',
            'lastName'  => 'Wawidowski',
            'login'     => 'dadamczyk',
            'roleId'    => null,
        ]);

        return $userId;
    }

    /**
     * @depends testCreate
     */
    public function testChangeUserData(UuidInterface $userId)
    {
        $this->userHelper->changeUserData([
            'id'        => $userId,
            'firstName' => 'romeczek',
            'lastName'  => 'romanowski',
            'login'     => 'romnowski',
            'password'  => 'leszcz124@#',
        ]);

        $this->userHelper->assertUpdateData($userId, [
            'id'        => $userId,
            'firstName' => 'romeczek',
            'lastName'  => 'romanowski',
            'login'     => 'romnowski',
        ]);
    }

    public function testChangeDataOnNonExistingUser()
    {
        $this->expectException(UserDoesNotExistException::class);

        $this->userHelper->changeUserData([
            'id'        => Uuid::uuid4(),
            'firstName' => 'romeczek',
            'lastName'  => 'romanowski',
            'login'     => 'romnowski',
            'password'  => 'leszcz124@#',
        ]);
    }

    /**
     * @depends testCreate
     */
    public function testRemoveUser(UuidInterface $userId)
    {
        $this->userHelper->remove([
            'id' => $userId,
        ]);

        $this->userHelper->assertRemove($userId);
    }

    public function testRemoveNotExistingUser()
    {
        $this->expectException(UserDoesNotExistException::class);

        $this->userHelper->remove([
            'id' => Uuid::uuid4(),
        ]);
    }
}
