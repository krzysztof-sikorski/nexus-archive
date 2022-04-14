<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NexusRawData;
use DateTimeImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NexusRawData|null find($id, $lockMode = null, $lockVersion = null)
 * @method NexusRawData|null findOneBy(array $criteria, array $orderBy = null)
 * @method NexusRawData[]    findAll()
 * @method NexusRawData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class NexusRawDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: NexusRawData::class);
    }

    public function getTotalCount(): int
    {
        $queryBuilder = $this->createQueryBuilder(alias: 'nrd')
            ->select(select: 'COUNT(nrd)');

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getPartialCount(DateTimeImmutable $from, DateTimeImmutable $to): int
    {
        $queryBuilder = $this->createQueryBuilder(alias: 'nrd')
            ->select(select: 'COUNT(nrd)')
            ->where(predicates: 'nrd.submittedAt BETWEEN :from AND :to')
            ->setParameter(key: 'from', value: $from)
            ->setParameter(key: 'to', value: $to);

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }
}
