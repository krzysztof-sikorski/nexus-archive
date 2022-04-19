<?php

declare(strict_types=1);

namespace App\Service\Repository;

use App\Doctrine\Entity\UserAccessToken;
use Doctrine\ORM\EntityManagerInterface;

final class UserAccessTokenRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function findByValue(string $value): ?UserAccessToken
    {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select(select: 'uat')
            ->from(from: UserAccessToken::class, alias: 'uat')
            ->andWhere('uat.value = :value')
            ->setParameter(key: 'value', value: $value)
            ->andWhere('uat.validUntil > current_timestamp()');

        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getTotalCount(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select(select: 'COUNT(uat)')
            ->from(from: UserAccessToken::class, alias: 'uat');

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }
}
