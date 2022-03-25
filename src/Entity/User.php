<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contract\Entity\BaseEntityInterface;
use App\Contract\UserRoles;
use App\Repository\UserRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[
    ORM\Entity(repositoryClass: UserRepository::class),
    ORM\Table(name: '"user"'),
    ORM\UniqueConstraint(name: 'username_uniq', fields: ['username']),
]
class User extends BaseEntity
    implements BaseEntityInterface, UserInterface, PasswordAuthenticatedUserInterface, JsonSerializable
{
    public const DEFAULT_ROLE = UserRoles::ROLE_USER;

    public const USERNAME_MAX_LENGTH = 180;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_IMMUTABLE, nullable: false)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'username', type: Types::STRING, length: self::USERNAME_MAX_LENGTH, nullable: false)]
    private ?string $username = null;

    #[ORM\Column(name: 'roles', type: Types::JSON, nullable: false)]
    private array $roles = [];

    #[ORM\Column(name: 'password', type: Types::STRING, nullable: false)]
    private ?string $password = null;

    #[ORM\Column(name: 'enabled', type: Types::BOOLEAN, nullable: false)]
    private bool $enabled = false;

    public function __toString(): string
    {
        return $this->getUserIdentifier();
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
        if (false === in_array(needle: self::DEFAULT_ROLE, haystack: $roles, strict: true)) {
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

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'createdAt' => $this->getCreatedAt()?->format(format: DateTimeInterface::ISO8601),
            'userIdentifier' => $this->getUserIdentifier(),
            'roles' => $this->getRoles(),
            'enabled' => $this->isEnabled(),
        ];
    }
}
