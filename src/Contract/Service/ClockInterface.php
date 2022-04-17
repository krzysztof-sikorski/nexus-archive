<?php

declare(strict_types=1);

namespace App\Contract\Service;

use DateTimeImmutable;
use DateTimeZone;

interface ClockInterface
{
    public function getUtcTimeZone(): DateTimeZone;

    public function getCurrentDateTime(): DateTimeImmutable;
}
