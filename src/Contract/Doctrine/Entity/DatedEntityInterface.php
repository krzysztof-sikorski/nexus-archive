<?php

declare(strict_types=1);

namespace App\Contract\Doctrine\Entity;

use DateTimeImmutable;

interface DatedEntityInterface
{
    public function getCreatedAt(): ?DateTimeImmutable;

    public function setCreatedAt(DateTimeImmutable $createdAt): void;

    public function getLastModifiedAt(): ?DateTimeImmutable;

    public function setLastModifiedAt(DateTimeImmutable $lastModifiedAt): void;
}
