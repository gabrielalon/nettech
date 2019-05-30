<?php

namespace App\Domain\Role\Testing;

use App\Domain\Role\Command\CreateRole;
use App\Domain\Role\Entity\Role;
use App\Domain\Role\Query\FindRole;
use App\Domain\Role\Service\PermissionService;
use App\Domain\Role\ValueObject\Permission;
use App\Infrastructure\Common\HandleTrait;
use App\Infrastructure\Testing\AbstractHelper;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoleHelper extends AbstractHelper
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

    /**
     * @var PermissionService
     */
    private $permissionService;

    public function __construct(
        TestCase $testCase,
        MessageBusInterface $commandBus,
        MessageBusInterface $queryBus,
        PermissionService $permissionService
    ) {
        $this->testCase = $testCase;
        $this->commandBus = $commandBus;
        $this->queryBus = $queryBus;
        $this->permissionService = $permissionService;
    }

    public function create(array $data): void
    {
        $resolver = new OptionsResolver();
        $resolver->setRequired(['id']);
        $resolver->setDefaults([
            'name'       => 'jakas-tam-rola',
            'permission' => [
                new Permission(
                    'remove',
                    'remove users',
                    1
                ),
            ],
        ]);
        $data = $resolver->resolve($data);

        $this->commandBus->dispatch(new CreateRole(
            $data['id'],
            $data['name'],
            $data['permission']
        ));
    }

    public function assertCreate(string $id, array $data)
    {
        /** @var Role $role */
        $role = $this->handle($this->queryBus, new FindRole($id));

        $permission = $this->permissionService->getPermission(
            $data['permission'][0]->getSubject(),
            $data['permission'][0]->getAction()
        );

        $this->runAssertions([
            'id'     => function ($expected, Role $role) {
                $this->testCase->assertEquals($expected, $role->getAggregateId());
            },
            'name'   => function ($expected, Role $role) {
                $this->testCase->assertEquals($expected, $role->getName());
            },
            'street' => function ($expected, Role $role) use ($permission) {
                $this->testCase->assertEquals(
                    $expected,
                    $role->hasPermission($permission->getAction(), $permission->getSubject())
                );
            },
        ], $role, $data);
    }
}
