<?php

declare(strict_types=1);

namespace App\Doctrine\Entity;

use App\Contract\Config\AppParameters;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * Implementation for `\App\Contract\Doctrine\Entity\UuidPrimaryKeyInterface` interface
 */
trait UuidPrimaryKeyTrait
{
    #[
        ORM\Id,
        ORM\Column(name: 'id', type: AppParameters::DOCTRINE_COLUMN_TYPE_UUID),
    ]
    protected Uuid $id;

    public function generateId(): void
    {
        $this->setId(id: Uuid::v4());
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function setId(Uuid $id): void
    {
        $this->id = $id;
    }
}
