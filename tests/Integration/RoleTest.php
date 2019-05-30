<?php

namespace App\Tests\Integration;

use App\Domain\Role\Service\PermissionService;
use App\Domain\Role\Testing\RoleHelper;
use App\Domain\Role\ValueObject\Permission;
use App\Infrastructure\Testing\DomainTestCase;
use Ramsey\Uuid\Uuid;

class RoleTest extends DomainTestCase
{
    /**
     * @var RoleHelper
     */
    private $roleHelper;

    protected function setUp(): void
    {
        parent::setUp();
        /** @var PermissionService $permissionService * */
        $permissionService = static::$container->get(PermissionService::class);

        $this->roleHelper = new RoleHelper(
            $this,
            $this->domainCommandBus,
            $this->domainQueryBus,
            $permissionService
        );
    }

    /**
     * TODO do ogarnięcia sprawdzenie czy rola istnieje.
     */
    public function testCreate(): void
    {
        $this->markTestSkipped();

        $roleId = Uuid::uuid4();

        $this->roleHelper->create([
            'id'         => $roleId,
            'name'       => 'adding users',
            'permission' => [
                new Permission(
                    'add',
                    'adding users',
                    1
                ),
            ],
        ]);

        $this->roleHelper->assertCreate($roleId, [
            'id'         => $roleId,
            'name'       => 'adding users',
            'permission' => [
                new Permission(
                    'add',
                    'adding users',
                    1
                ),
            ],
        ]);
    }
}
