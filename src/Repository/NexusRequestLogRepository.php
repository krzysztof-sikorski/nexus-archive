<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NexusRequestLog;
use App\Entity\NexusRequestLog\Request;
use DateTimeInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method NexusRequestLog|null find($id, $lockMode = null, $lockVersion = null)
 * @method NexusRequestLog|null findOneBy(array $criteria, array $orderBy = null)
 * @method NexusRequestLog[]    findAll()
 * @method NexusRequestLog[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class NexusRequestLogRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, NexusRequestLog::class);
    }

    public function findByRequestData(Request $request): mixed
    {
        $queryBuilder = $this->createQueryBuilder('nrl')
            ->andWhere('nrl.request.startedAt = :startedAt')
            ->setParameter('startedAt', $request->getStartedAt()?->format(DateTimeInterface::ISO8601))
            ->andWhere('nrl.request.id = :id')
            ->setParameter('id', $request->getId());

        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }

    // /**
    //  * @return NexusRequestLog[] Returns an array of NexusRequestLog objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NexusRequestLog
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
