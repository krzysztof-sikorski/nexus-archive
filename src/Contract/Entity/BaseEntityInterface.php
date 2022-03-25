<?php

declare(strict_types=1);

namespace App\Contract\Entity;

use Symfony\Component\Uid\Uuid;

interface BaseEntityInterface
{
    public function getId(): Uuid;

    public function setId(Uuid $id): void;
}
