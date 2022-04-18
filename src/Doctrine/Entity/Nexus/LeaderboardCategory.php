<?php

declare(strict_types=1);

namespace App\Doctrine\Entity\Nexus;

use App\Contract\Doctrine\Entity\UuidPrimaryKeyInterface;
use App\Contract\Entity\LeaderboardTypes;
use App\Doctrine\Entity\UuidPrimaryKeyTrait;
use App\Doctrine\Repository\Nexus\LeaderboardCategoryRepository;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: LeaderboardCategoryRepository::class),
    ORM\Table(name: 'nexus_leaderboard_category'),
    ORM\UniqueConstraint(name: 'nexus_leaderboard_category_uniq', fields: ['name', 'career']),
]
class LeaderboardCategory implements UuidPrimaryKeyInterface
{
    use UuidPrimaryKeyTrait;

    #[ORM\Column(name: 'name', type: 'text', nullable: false)]
    private ?string $name = null;

    #[ORM\Column(name: 'score_label', type: 'text', nullable: false)]
    private ?string $scoreLabel = null;

    #[ORM\Column(name: 'career', type: 'boolean', nullable: false)]
    private bool $career = false;

    public function __construct()
    {
        $this->generateId();
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getScoreLabel(): ?string
    {
        return $this->scoreLabel;
    }

    public function setScoreLabel(string $scoreLabel): void
    {
        $this->scoreLabel = $scoreLabel;
    }

    public function getType(): ?string
    {
        return $this->career ? LeaderboardTypes::CAREER : LeaderboardTypes::BREATH;
    }

    public function setType(string $type): void
    {
        $this->career = LeaderboardTypes::CAREER === $type;
    }

    public function getCareer(): bool
    {
        return $this->career;
    }

    public function setCareer(bool $career): self
    {
        $this->career = $career;

        return $this;
    }
}
