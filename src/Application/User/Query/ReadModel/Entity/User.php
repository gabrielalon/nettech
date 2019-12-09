<?php

namespace App\Application\User\Query\ReadModel\Entity;

use Doctrine\ORM\Mapping as ORM;
use N3ttech\Messaging\Query\Query;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persist\User\DoctrineUserRepository")
 * @ORM\Table(
 *     name="user",
 *     uniqueConstraints={
 *      @ORM\UniqueConstraint(name="login", columns={"login"})},
 *     indexes={
 *      @ORM\Index(name="created_at", columns={"created_at"})
 *     }
 * )
 */
class User implements UserInterface, Query\Viewable
{
    /**
     * @var \Ramsey\Uuid\UuidInterface
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true, columnDefinition="CHAR(36) NOT NULL")
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="Ramsey\Uuid\Doctrine\UuidGenerator")
     */
    private $uuid;

    /**
     * @var string
     * @ORM\Column(type="string", name="login")
     */
    private $login;

    /**
     * @var string
     * @ORM\Column(type="string", name="password")
     */
    private $password;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz", name="created_at")
     */
    private $createdAt;

    /**
     * User constructor.
     * @param string $uuid
     * @param string $login
     * @param string $password
     * @param \DateTimeImmutable $createdAt
     */
    public function __construct(
        string $uuid,
        string $login,
        string $password,
        \DateTimeImmutable $createdAt
    ) {
        $this->uuid = $uuid;
        $this->login = $login;
        $this->password = $password;
        $this->createdAt = $createdAt;
    }

    /**
     * {@inheritDoc}
     */
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    /**
     * {@inheritDoc}
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * {@inheritDoc}
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function getUsername(): string
    {
        return $this->getLogin();
    }

    /**
     * {@inheritDoc}
     */
    public function eraseCredentials(): void
    {
        // Do nothing
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return (string) $this->uuid;
    }

    /**
     * @return string
     */
    public function getLogin(): string
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * {@inheritDoc}
     */
    public function identifier()
    {
        return $this->getUuid();
    }

    /**
     * @return \DateTime
     */
    public function creationDate(): \DateTime
    {
        return date_timestamp_set(date_create(), $this->createdAt->getTimestamp());
    }
}
