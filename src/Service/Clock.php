<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Service\ClockInterface;
use DateTimeImmutable;
use DateTimeZone;

final class Clock implements ClockInterface
{
    public function getCurrentDateTime(): DateTimeImmutable
    {
        $timezone = new DateTimeZone(timezone: 'UTC');
        return new DateTimeImmutable(datetime: 'now', timezone: $timezone);
    }
}
