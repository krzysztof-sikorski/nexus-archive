<?php

declare(strict_types=1);

namespace App\Doctrine\Entity;

use App\Contract\Config\AppParameters;
use App\Contract\Doctrine\Entity\BaseEntityInterface;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

abstract class BaseEntity implements BaseEntityInterface
{
    #[
        ORM\Id,
        ORM\Column(name: 'id', type: AppParameters::DOCTRINE_COLUMN_TYPE_UUID),
    ]
    protected Uuid $id;

    #[ORM\Column(name: 'created_at', type: Types::DATETIMETZ_IMMUTABLE, nullable: false)]
    protected ?DateTimeImmutable $createdAt = null;

    #[ORM\Column(name: 'last_modified_at', type: Types::DATETIMETZ_IMMUTABLE, nullable: false)]
    protected ?DateTimeImmutable $lastModifiedAt = null;

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
