<?php

namespace App\Domain\User\ReadModel\Entity;

use App\Domain\Role\ReadModel\Entity\Role;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Infrastructure\Persistence\User\Repository\UserRepository")
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{
    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="guid")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", name="first_name")
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", name="last_name")
     */
    private $lastName;

    /**
     * @var string
     * @ORM\Column(type="string", name="login")
     */
    private $login;

    /**
     * @var string
     * @ORM\Column(type="string", name="password_hash")
     */
    private $passwordHash;

    /**
     * @ORM\OneToOne(targetEntity="App\Domain\Role\ReadModel\Entity\Role")
     * @ORM\JoinColumn(name="role_id", referencedColumnName="id")
     */
    private $role;

    /**
     * @var \DateTimeImmutable
     * @ORM\Column(type="datetimetz", name="created_at")
     */
    private $createdAt;

    public function __construct(
        string $id,
        string $firstName,
        string $lastName,
        string $login,
        string $passwordHash,
        ?Role $role,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
        $this->login = $login;
        $this->passwordHash = $passwordHash;
        $this->role = $role;
        $this->createdAt = $createdAt;
    }

    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }

    public function getPassword(): string
    {
        return $this->passwordHash;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->login;
    }

    public function setUsername(string $username): void
    {
        $this->login = $username;
    }

    public function eraseCredentials(): void
    {
        // Do nothing
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function setLogin(string $login): void
    {
        $this->login = $login;
    }

    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): void
    {
        $this->role = $role;
    }
}
