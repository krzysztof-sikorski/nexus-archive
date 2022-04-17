<?php

declare(strict_types=1);

namespace App\Doctrine\Repository\Nexus;

use App\Doctrine\Entity\Nexus\GamePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method GamePeriod|null find($id, $lockMode = null, $lockVersion = null)
 * @method GamePeriod|null findOneBy(array $criteria, array $orderBy = null)
 * @method GamePeriod[]    findAll()
 * @method GamePeriod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GamePeriodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GamePeriod::class);
    }
}
