<?php

namespace App\Domain\Model\User;

use App\Application\User\Event;
use N3ttech\Messaging\Aggregate\AggregateRoot;
use N3ttech\Valuing as VO;

class User extends AggregateRoot
{
    /** @var VO\Date\Time */
    private $creationDate;

    /** @var VO\Char\Text */
    private $login;

    /** @var VO\Char\Text */
    private $password;

    /**
     * @param VO\Identity\Uuid $uuid
     *
     * @return User
     */
    public function setUuid(VO\Identity\Uuid $uuid): User
    {
        $this->setAggregateId($uuid);

        return $this;
    }

    /**
     * @param VO\Date\Time $creationDate
     *
     * @return User
     */
    public function setCreationDate(VO\Date\Time $creationDate): User
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    /**
     * @param VO\Char\Text $login
     *
     * @return User
     */
    public function setLogin(VO\Char\Text $login): User
    {
        $this->login = $login;

        return $this;
    }

    /**
     * @param VO\Char\Text $password
     *
     * @return User
     */
    public function setPassword(VO\Char\Text $password): User
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param VO\Identity\Uuid $uuid
     * @param VO\Char\Text     $login
     * @param VO\Char\Text     $password
     *
     * @return User
     */
    public static function createNewUser(
        VO\Identity\Uuid $uuid,
        VO\Char\Text $login,
        VO\Char\Text $password
    ): User {
        $category = new self();

        $category->recordThat(Event\NewUserCreated::occur($uuid->toString(), [
            'creation_date' => time(),
            'login' => $login->toString(),
            'password' => $password->toString(),
        ]));

        return $category;
    }

    public function remove(): void
    {
        $this->recordThat(Event\ExistingUserRemoved::occur($this->aggregateId()));
    }
}
