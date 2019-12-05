<?php

namespace App\Application\User\Event;

use App\Domain\Model\User\User;
use N3ttech\Messaging\Aggregate\AggregateRoot;
use N3ttech\Valuing as VO;

class NewUserCreated extends UserEvent
{
    /**
     * @return VO\Date\Time
     * @throws \Assert\AssertionFailedException
     */
    public function userCreationDate(): VO\Date\Time
    {
        return VO\Date\Time::fromTimestamp($this->payload['creation_date']);
    }

    /**
     * @return VO\Char\Text
     * @throws \Assert\AssertionFailedException
     */
    public function userLogin(): VO\Char\Text
    {
        return VO\Char\Text::fromString($this->payload['login']);
    }

    /**
     * @return VO\Char\Text
     * @throws \Assert\AssertionFailedException
     */
    public function userPassword(): VO\Char\Text
    {
        return VO\Char\Text::fromString($this->payload['password']);
    }

    /**
     * @param User $user
     * @throws \Assert\AssertionFailedException
     */
    public function populate(AggregateRoot $user): void
    {
        $user->setUuid($this->userUuid());
        $user->setCreationDate($this->userCreationDate());
        $user->setLogin($this->userLogin());
        $user->setPassword($this->userPassword());
    }
}
