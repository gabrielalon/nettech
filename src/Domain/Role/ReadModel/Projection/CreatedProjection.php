<?php

namespace App\Domain\Role\ReadModel\Projection;

use App\Domain\Role\Event\Created;
use App\Domain\Role\ReadModel\Entity\Role;
use App\Domain\Role\ReadModel\Repository\RoleRepositoryInterface;
use App\Domain\Role\ValueObject\Permission;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreatedProjection implements MessageHandlerInterface
{
    /**
     * @var RoleRepositoryInterface
     */
    private $roleRepository;

    public function __construct(RoleRepositoryInterface $roleRepository)
    {
        $this->roleRepository = $roleRepository;
    }

    public function __invoke(Created $event)
    {
        $this->roleRepository->save(new Role(
            $event->getRoleId()->__toString(),
            $event->getName()->getValue(),
            array_map(
                function (Permission $permission) {
                    return $permission->__toString();
                },
                $event->getPermissions()->toArray()
            ),
            $event->getCreatedDate()
        ));
    }
}
