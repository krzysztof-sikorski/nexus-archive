<?php

declare(strict_types=1);

namespace App\Entity;

use App\Contract\Config\AppParameters;
use App\Contract\Entity\BaseEntityInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

abstract class BaseEntity implements BaseEntityInterface
{
    #[
        ORM\Id,
        ORM\Column(name: 'id', type: AppParameters::DOCTRINE_COLUMN_TYPE_UUID),
    ]
    protected Uuid $id;

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
}
