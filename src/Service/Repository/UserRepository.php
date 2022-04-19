<?php

declare(strict_types=1);

namespace App\Service\Repository;

use App\Doctrine\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

final class UserRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function findByUsername(string $username): ?User
    {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select(select: 'u')
            ->from(from: User::class, alias: 'u')
            ->andWhere('u.username = :username')
            ->setParameter(key: 'username', value: $username);

        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getTotalCount(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select(select: 'COUNT(u)')
            ->from(from: User::class, alias: 'u');

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }
}
