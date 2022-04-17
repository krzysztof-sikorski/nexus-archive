<?php

declare(strict_types=1);

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\UserAccessToken;
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
        parent::__construct(registry: $registry, entityClass: UserAccessToken::class);
    }

    public function findByValue(string $value): ?UserAccessToken
    {
        $queryBuilder = $this->createQueryBuilder(alias: 'uat')
            ->andWhere('uat.value = :value')
            ->setParameter(key: 'value', value: $value)
            ->andWhere('uat.validUntil > current_timestamp()');

        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getTotalCount(): int
    {
        $queryBuilder = $this->createQueryBuilder(alias: 'uat')
            ->select(select: 'COUNT(uat)');

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }
}
