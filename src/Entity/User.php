<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contract\UserRoles;
use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
final class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const DEFAULT_ROLE = UserRoles::ROLE_USER;

    private const USERNAME_MAX_LENGTH = 180;

    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(name: 'created_at', type: 'datetimetz_immutable', nullable: false)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'username', type: 'string', length: self::USERNAME_MAX_LENGTH, unique: true, nullable: false)]
    private ?string $username = null;

    #[ORM\Column(name: 'roles', type: 'json', nullable: false, options: ['default' => '[]'])]
    private array $roles = [];

    #[ORM\Column(name: 'password', type: 'string', nullable: false)]
    private ?string $password = null;

    #[ORM\Column(name: 'enabled', type: 'boolean', nullable: false)]
    private bool $enabled = false;

    public function __construct(?Uuid $id = null)
    {
        $this->id = $id ?? Uuid::v4();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string)$this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantee every user at least has default role
        if (false === in_array(self::DEFAULT_ROLE, $roles, true)) {
            $roles[] = self::DEFAULT_ROLE;
        }

        return $roles;
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): void
    {
        $this->enabled = $enabled;
    }
}
