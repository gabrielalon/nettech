<?php

declare(strict_types=1);

namespace App\Domain\User\Event;

use App\Domain\User\ValueObject\FirstName;
use App\Domain\User\ValueObject\LastName;
use App\Domain\User\ValueObject\Login;
use App\Domain\User\ValueObject\Password;
use App\Domain\User\ValueObject\UserId;
use App\Infrastructure\EventSourcing\Event\DomainEventInterface;

final class Created implements DomainEventInterface
{
    /**
     * @var UserId
     */
    private $userId;

    /**
     * @var FirstName
     */
    private $firstName;

    /**
     * @var LastName
     */
    private $lastName;

    /**
     * @var Login
     */
    private $login;

    /**
     * @var Password
     */
    private $password;

    public function __construct(
        UserId $userId,
        FirstName $firstName,
        LastName $lastName,
        Login $login,
        Password $password
    ) {
        $this->userId = $userId;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->login = $login;
        $this->password = $password;
    }

    public function getFirstName(): FirstName
    {
        return $this->firstName;
    }

    public function getLastName(): LastName
    {
        return $this->lastName;
    }

    public function getLogin(): Login
    {
        return $this->login;
    }

    public function getPasswordHash(): Password
    {
        return $this->password;
    }

    public function getUserId(): UserId
    {
        return $this->userId;
    }
}
