<?php

namespace App\Infrastructure\Projection\User;

use App\Application\User\Event;
use App\Domain\Model\User\Projection\UserProjector;
use App\Infrastructure\Doctrine\DatabaseConnected;
use App\Infrastructure\PasswordHasher\PasswordHasherInterface;
use Doctrine\DBAL\ParameterType;

class DoctrineUserProjector extends DatabaseConnected implements UserProjector
{
    /** @var PasswordHasherInterface */
    private $passwordHasher;

    /**
     * @required
     * @param PasswordHasherInterface $passwordHasher
     */
    public function setPasswordHasher(PasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @inheritDoc
     */
    public function onNewUserCreated(Event\NewUserCreated $event): void
    {
        $creationDate = date_timestamp_set(date_create(), $event->userCreationDate()->raw());

        $query = '
            INSERT `user` (`uuid`, `login`, `password`, `created_at`)
            VALUES (:uuid, :login, :password, :created_at)
        ';

        $statement = $this->connection->prepare($query);
        $statement->execute([
            'uuid' => $event->userUuid()->toString(),
            'login' => $event->userLogin()->toString(),
            'password' => $this->passwordHasher->hash($event->userPassword()->toString()),
            'created_at' => \DateTimeImmutable::createFromMutable($creationDate)->format('Y-m-d H:i:s')
        ]);
    }

    /**
     * @inheritDoc
     */
    public function onExistingUserRemoved(Event\ExistingUserRemoved $event): void
    {
        $query = '
            DELETE FROM 
                `user`
            WHERE 
                `uuid` = :uuid
        ';

        $statement = $this->connection->prepare($query);
        $statement->execute(['uuid' => $event->userUuid()->toString()]);
    }
}
