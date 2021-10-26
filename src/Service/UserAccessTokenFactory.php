<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\UserAccessToken;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

final class UserAccessTokenFactory
{
    public function __construct(
        private ClockInterface $clock,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function create(string $value, DateInterval $duration): UserAccessToken
    {
        $uuid = Uuid::v4();
        $createdAt = $this->clock->getCurrentDateTime();
        $validUntil = $createdAt->add($duration);

        $token = new UserAccessToken($uuid);
        $token->setValue($value);
        $token->setCreatedAt($createdAt);
        $token->setValidUntil($validUntil);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $token;
    }
}
