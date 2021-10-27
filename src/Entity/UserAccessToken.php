<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserAccessTokenRepository;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\ArrayShape;
use JsonSerializable;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserAccessTokenRepository::class)]
final class UserAccessToken implements JsonSerializable
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[ORM\Column(type: 'text', unique: true, nullable: false)]
    private ?string $value;

    #[ORM\Column(type: 'datetimetz_immutable', nullable: false)]
    private ?DateTimeImmutable $createdAt;

    #[ORM\Column(type: 'datetimetz_immutable', nullable: true)]
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

    #[ArrayShape([
        'id' => Uuid::class,
        'value' => "null|string",
        'createdAt' => "null|string",
        'validUntil' => "null|string",
    ])]
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
