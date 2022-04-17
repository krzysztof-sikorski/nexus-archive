<?php

declare(strict_types=1);

namespace App\Doctrine\Entity\Nexus;

use App\Doctrine\Entity\BaseEntity;
use App\Doctrine\Repository\Nexus\LeaderboardEntryRepository;
use Doctrine\ORM\Mapping as ORM;

#[
    ORM\Entity(repositoryClass: LeaderboardEntryRepository::class),
    ORM\Table(name: 'nexus_leaderboard_entry'),
    ORM\UniqueConstraint(name: 'nexus_leaderboard_entry_uniq', fields: ['position']),
    ORM\Index(fields: ['leaderboard'], name: 'nexus_leaderboard_entry_leaderboard_idx'),
]
class LeaderboardEntry extends BaseEntity
{
    #[
        ORM\ManyToOne(targetEntity: Leaderboard::class),
        ORM\JoinColumn(name: 'leaderboard_id', referencedColumnName: 'id', nullable: false),
    ]
    private ?Leaderboard $leaderboard = null;

    #[ORM\Column(name: 'character_name', type: 'text', nullable: false)]
    private ?string $characterName = null;

    #[ORM\Column(name: 'position', type: 'integer', nullable: false)]
    private ?int $position = null;

    #[ORM\Column(name: 'value', type: 'integer', nullable: false)]
    private ?int $value = null;

    public function getLeaderboard(): ?Leaderboard
    {
        return $this->leaderboard;
    }

    public function setLeaderboard(?Leaderboard $leaderboard): void
    {
        $this->leaderboard = $leaderboard;
    }

    public function getCharacterName(): ?string
    {
        return $this->characterName;
    }

    public function setCharacterName(string $characterName): void
    {
        $this->characterName = $characterName;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): void
    {
        $this->position = $position;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): void
    {
        $this->value = $value;
    }
}
