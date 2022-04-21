<?php

declare(strict_types=1);

namespace App\Contract\Service\Parser;

use App\Contract\Entity\Nexus\GamePeriodInterface;
use App\Contract\Entity\Nexus\LeaderboardInterface;

/**
 * Result of parsing raw data
 */
interface ParserResultInterface
{
    public function hasErrors(): bool;

    public function getErrors(): ?array;

    public function getGamePeriod(): ?GamePeriodInterface;

    public function setGamePeriod(GamePeriodInterface $gamePeriod): void;

    public function getLeaderboard(): ?LeaderboardInterface;

    public function setLeaderboard(LeaderboardInterface $leaderboard): void;
}
