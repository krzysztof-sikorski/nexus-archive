<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserAccessToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserAccessToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserAccessToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserAccessToken[]    findAll()
 * @method UserAccessToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class UserAccessTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAccessToken::class);
    }

    public function findByValue(string $value): ?UserAccessToken
    {
        $queryBuilder = $this->createQueryBuilder('uat')
            ->andWhere('uat.value = :value')
            ->setParameter('value', $value)
            ->andWhere('uat.validUntil > current_timestamp()');

        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }

    // /**
    //  * @return UserAccessToken[] Returns an array of UserAccessToken objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UserAccessToken
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
