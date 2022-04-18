<?php

declare(strict_types=1);

namespace App\Doctrine\Repository\Nexus;

use App\Doctrine\Entity\Nexus\LeaderboardCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method LeaderboardCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method LeaderboardCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method LeaderboardCategory[]    findAll()
 * @method LeaderboardCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LeaderboardCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LeaderboardCategory::class);
    }
}
