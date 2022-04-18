<?php

declare(strict_types=1);

namespace App\Doctrine\Entity\Nexus;

use App\Contract\Doctrine\Entity\DatedEntityInterface;
use App\Contract\Doctrine\Entity\GamePeriodReferenceInterface;
use App\Contract\Doctrine\Entity\UuidPrimaryKeyInterface;
use App\Contract\Entity\Nexus\GamePeriodInterface;
use App\Doctrine\Entity\DatedEntityTrait;
use App\Doctrine\Entity\UuidPrimaryKeyTrait;
use App\Doctrine\Repository\Nexus\LeaderboardRepository;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: LeaderboardRepository::class),
    ORM\Table(name: 'nexus_leaderboard'),
    ORM\UniqueConstraint(name: 'nexus_leaderboard_uniq', fields: ['category', 'gamePeriod']),
    ORM\Index(fields: ['category'], name: 'nexus_leaderboard_category_idx'),
    ORM\Index(fields: ['gamePeriod'], name: 'nexus_leaderboard_game_period_idx'),
]
class Leaderboard implements UuidPrimaryKeyInterface, GamePeriodReferenceInterface, DatedEntityInterface
{
    use UuidPrimaryKeyTrait;
    use DatedEntityTrait;

    #[
        ORM\ManyToOne(targetEntity: LeaderboardCategory::class),
        ORM\JoinColumn(name: 'category_id', referencedColumnName: 'id', nullable: false),
    ]
    private ?LeaderboardCategory $category = null;

    #[
        ORM\ManyToOne(targetEntity: GamePeriod::class),
        ORM\JoinColumn(name: 'game_period_id', referencedColumnName: 'id', nullable: false),
    ]
    protected ?GamePeriodInterface $gamePeriod = null;

    public function __construct()
    {
        $this->generateId();
    }

    public function getCategory(): ?LeaderboardCategory
    {
        return $this->category;
    }

    public function setCategory(LeaderboardCategory $category): void
    {
        $this->category = $category;
    }

    public function getGamePeriod(): ?GamePeriodInterface
    {
        return $this->gamePeriod;
    }

    public function setGamePeriod(GamePeriodInterface $gamePeriod): void
    {
        $this->gamePeriod = $gamePeriod;
    }
}
