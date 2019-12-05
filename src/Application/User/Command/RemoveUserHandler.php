<?php

namespace App\Application\User\Command;

use App\Domain\Model\User\User;
use N3ttech\Messaging\Message\Domain\Message;

class RemoveUserHandler extends UserCommandHandler
{
    /**
     * @param RemoveUser $command
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function run(Message $command): void
    {
        /** @var User $user */
        $user = $this->repository->find($command->getUuid());

        $user->remove();

        $this->repository->save($user);
    }
}
