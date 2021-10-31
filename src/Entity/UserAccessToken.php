<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserAccessTokenRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserAccessTokenRepository::class)]
#[ORM\Table(name: 'user_access_token')]
final class UserAccessToken implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(name: 'id', type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(name: 'value', type: 'text', unique: true, nullable: false)]
    private ?string $value;

    #[ORM\Column(name: 'created_at', type: 'datetimetz_immutable', nullable: false)]
    private ?DateTimeImmutable $createdAt;

    #[ORM\Column(name: 'valid_until', type: 'datetimetz_immutable', nullable: true)]
    private ?DateTimeImmutable $validUntil;

    public function __construct(?Uuid $id = null)
    {
        $this->id = $id ?? Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getValidUntil(): ?DateTimeImmutable
    {
        return $this->validUntil;
    }

    public function setValidUntil(?DateTimeImmutable $validUntil): void
    {
        $this->validUntil = $validUntil;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->getId(),
            'value' => $this->getValue(),
            'createdAt' => $this->getCreatedAt()?->format(DateTimeInterface::ISO8601),
            'validUntil' => $this->getValidUntil()?->format(DateTimeInterface::ISO8601),
        ];
    }
}
