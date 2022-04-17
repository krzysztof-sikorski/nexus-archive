<?php

declare(strict_types=1);

namespace App\Contract\Doctrine\Entity;

use DateTimeImmutable;
use Symfony\Component\Uid\Uuid;

interface BaseEntityInterface
{
    public function getId(): Uuid;

    public function setId(Uuid $id): void;

    public function getCreatedAt(): ?DateTimeImmutable;

    public function setCreatedAt(DateTimeImmutable $createdAt): void;

    public function getLastModifiedAt(): ?DateTimeImmutable;

    public function setLastModifiedAt(DateTimeImmutable $lastModifiedAt): void;
}
