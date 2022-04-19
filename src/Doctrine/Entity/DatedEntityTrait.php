<?php

declare(strict_types=1);

namespace App\Doctrine\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Implementation for `\App\Contract\Doctrine\Entity\DatedEntityInterface` interface
 */
trait DatedEntityTrait
{
    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_IMMUTABLE, nullable: false)]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'last_modified_at', type: Types::DATETIMETZ_IMMUTABLE, nullable: false)]
    protected ?DateTimeImmutable $lastModifiedAt = null;

    public function getCreatedAt(): ?DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeInterface $createdAt): void
    {
        $this->createdAt = DateTimeImmutable::createFromInterface(object: $createdAt);
    }

    public function getLastModifiedAt(): ?DateTimeInterface
    {
        return $this->lastModifiedAt;
    }

    public function setLastModifiedAt(DateTimeInterface $lastModifiedAt): void
    {
        $this->lastModifiedAt = DateTimeImmutable::createFromInterface(object: $lastModifiedAt);
    }
}
