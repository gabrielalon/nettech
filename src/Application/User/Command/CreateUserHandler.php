<?php

namespace App\Application\User\Command;

use App\Domain\Model\User\User;
use N3ttech\Messaging\Message\Domain\Message;
use N3ttech\Valuing as VO;

class CreateUserHandler extends UserCommandHandler
{
    /**
     * @param CreateUser $command
     *
     * @throws \Assert\AssertionFailedException
     * @throws \Exception
     */
    public function run(Message $command): void
    {
        $this->repository->save(User::createNewUser(
            VO\Identity\Uuid::fromIdentity($command->getUuid()),
            VO\Char\Text::fromString($command->getLogin()),
            VO\Char\Text::fromString($command->getPassword())
        ));
    }
}
