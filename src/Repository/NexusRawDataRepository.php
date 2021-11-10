<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\NexusRawData;
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
        parent::__construct($registry, NexusRawData::class);
    }

    // /**
    //  * @return NexusRawData[] Returns an array of NexusRawData objects
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
    public function findOneBySomeField($value): ?NexusRawData
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
