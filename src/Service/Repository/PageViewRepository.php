<?php

declare(strict_types=1);

namespace App\Service\Repository;

use App\Doctrine\Entity\PageView;
use App\Doctrine\Entity\User;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

final class PageViewRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function persist(User $owner, PageView $pageView, DateTimeInterface $currentDateTime): void
    {
        $pageView->setCreatedAt(createdAt: $currentDateTime);
        $pageView->setLastModifiedAt(lastModifiedAt: $currentDateTime);
        $pageView->setOwner(owner: $owner);
        $this->entityManager->persist($pageView);
        $this->entityManager->flush();
    }

    public function getTotalCount(): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select(select: 'COUNT(pv)')
            ->from(from: PageView::class, alias: 'pv');

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getPartialCount(DateTimeInterface $from, DateTimeInterface $to): int
    {
        $queryBuilder = $this->entityManager->createQueryBuilder()
            ->select(select: 'COUNT(pv)')
            ->from(from: PageView::class, alias: 'pv')
            ->where(predicates: 'pv.createdAt BETWEEN :from AND :to')
            ->setParameter(key: 'from', value: $from)
            ->setParameter(key: 'to', value: $to);

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }
}
