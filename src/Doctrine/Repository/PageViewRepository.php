<?php

declare(strict_types=1);

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\PageView;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PageView|null find($id, $lockMode = null, $lockVersion = null)
 * @method PageView|null findOneBy(array $criteria, array $orderBy = null)
 * @method PageView[]    findAll()
 * @method PageView[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class PageViewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: PageView::class);
    }

    public function getTotalCount(): int
    {
        $queryBuilder = $this->createQueryBuilder(alias: 'pv')
            ->select(select: 'COUNT(pv)');

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getPartialCount(DateTimeImmutable $from, DateTimeImmutable $to): int
    {
        $queryBuilder = $this->createQueryBuilder(alias: 'pv')
            ->select(select: 'COUNT(pv)')
            ->where(predicates: 'pv.createdAt BETWEEN :from AND :to')
            ->setParameter(key: 'from', value: $from)
            ->setParameter(key: 'to', value: $to);

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }
}
