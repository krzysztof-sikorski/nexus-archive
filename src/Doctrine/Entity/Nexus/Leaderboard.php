<?php

declare(strict_types=1);

namespace App\Doctrine\Entity\Nexus;

use App\Contract\Entity\LeaderboardTypes;
use App\Doctrine\Entity\BaseEntity;
use App\Doctrine\Repository\Nexus\LeaderboardRepository;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: LeaderboardRepository::class),
    ORM\Table(name: 'nexus_leaderboard'),
    ORM\UniqueConstraint(name: 'nexus_leaderboard_uniq', fields: ['title']),
]
class Leaderboard extends BaseEntity
{
    #[ORM\Column(name: 'title', type: 'text', nullable: false)]
    private ?string $title = null;

    #[ORM\Column(name: 'value_title', type: 'text', nullable: false)]
    private ?string $valueTitle = null;

    #[ORM\Column(name: 'career', type: 'boolean', nullable: false)]
    private bool $career = false;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getValueTitle(): ?string
    {
        return $this->valueTitle;
    }

    public function setValueTitle(string $valueTitle): void
    {
        $this->valueTitle = $valueTitle;
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
