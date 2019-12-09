<?php

namespace App\Tests\Infrastructure\Projection\User;

use App\Application\User\Event;
use App\Application\User\Query\ReadModel;
use App\Domain\Model\User\Projection\UserProjector;

final class InMemoryUserProjector implements UserProjector
{
    /** @var ReadModel\Entity\UserCollection */
    private $users;

    /**
     * @param ReadModel\Entity\UserCollection|null $users
     */
    public function __construct(ReadModel\Entity\UserCollection $users = null)
    {
        if (null === $users) {
            $users = new ReadModel\Entity\UserCollection([]);
        }

        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Assert\AssertionFailedException
     */
    public function onNewUserCreated(Event\NewUserCreated $event): void
    {
        $this->users->add(new ReadModel\Entity\User(
            $event->userUuid()->toString(),
            $event->userLogin()->toString(),
            $event->userPassword()->toString(),
            \DateTimeImmutable::createFromMutable(date_create())
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function onExistingUserRemoved(Event\ExistingUserRemoved $event): void
    {
        $this->checkExistence($event->aggregateId());

        $this->users->remove($event->aggregateId());
    }

    /**
     * @param string $key
     *
     * @return ReadModel\Entity\User
     *
     * @throws \RuntimeException
     */
    public function get(string $key): ReadModel\Entity\User
    {
        $this->checkExistence($key);

        return $this->users->get($key);
    }

    /**
     * @param string $key
     *
     * @throws \RuntimeException
     */
    private function checkExistence(string $key): void
    {
        if (false === $this->users->has($key)) {
            throw new \RuntimeException(\sprintf('User does not exists on given key: %s', $key));
        }
    }
}
