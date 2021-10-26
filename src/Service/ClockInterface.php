<?php

declare(strict_types=1);

namespace App\Service;

use DateTimeImmutable;

interface ClockInterface
{
    public function getCurrentDateTime(): DateTimeImmutable;
}
