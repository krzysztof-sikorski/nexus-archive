<?php

declare(strict_types=1);

namespace App\Doctrine\Entity;

use DateTimeImmutable;
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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getLastModifiedAt(): ?DateTimeImmutable
    {
        return $this->lastModifiedAt;
    }

    public function setLastModifiedAt(DateTimeImmutable $lastModifiedAt): void
    {
        $this->lastModifiedAt = $lastModifiedAt;
    }
}
