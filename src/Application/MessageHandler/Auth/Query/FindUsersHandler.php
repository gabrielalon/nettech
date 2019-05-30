<?php

namespace App\Application\MessageHandler\Auth\Query;

use App\Application\Message\Auth\Query\FindUsers;
use App\Application\Message\Auth\Reply\Users;
use App\Application\Model;
use App\Domain\User\Query\FindUsers as DomainFindUsers;
use App\Domain\User\ReadModel\Entity\User;
use App\Infrastructure\Common\HandleTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class FindUsersHandler implements MessageHandlerInterface
{
    use HandleTrait;

    /**
     * @var MessageBusInterface
     */
    private $domainQueryBus;

    public function __construct(MessageBusInterface $domainQueryBus)
    {
        $this->domainQueryBus = $domainQueryBus;
    }

    public function __invoke(FindUsers $query): Users
    {
        /** @var User[] $users */
        $users = $this->handle($this->domainQueryBus, new DomainFindUsers($query->getFilters()));

        $result = [];
        foreach ($users as $user) {
            $result[] = new Model\User(
                $user->getId(),
                $user->getUsername(),
                $user->getPassword()
            );
        }

        return new Users($result);
    }
}
