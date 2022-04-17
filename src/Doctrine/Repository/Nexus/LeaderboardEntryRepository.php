<?php

declare(strict_types=1);

namespace App\Doctrine\Repository\Nexus;

use App\Doctrine\Entity\Nexus\LeaderboardEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeaderboardEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeaderboardEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeaderboardEntry[]    findAll()
 * @method LeaderboardEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeaderboardEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeaderboardEntry::class);
    }
}
