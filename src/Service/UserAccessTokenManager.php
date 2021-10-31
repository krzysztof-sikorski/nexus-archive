<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Service\ClockInterface;
use App\Entity\User;
use App\Entity\UserAccessToken;
use DateInterval;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\Uuid;

use function bin2hex;
use function random_bytes;

final class UserAccessTokenManager
{
    private const VALUE_BYTES_LENGTH = 32;

    public function __construct(
        private ClockInterface $clock,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function create(User $owner, DateInterval $duration): UserAccessToken
    {
        $uuid = Uuid::v4();
        $createdAt = $this->clock->getCurrentDateTime();
        $validUntil = $createdAt->add($duration);

        $token = new UserAccessToken($uuid);
        $token->setOwner($owner);
        $token->setValue($this->generateValue());
        $token->setCreatedAt($createdAt);
        $token->setValidUntil($validUntil);

        $this->entityManager->persist($token);
        $this->entityManager->flush();

        return $token;
    }

    public function prune(): void
    {
        $dql = 'DELETE FROM App:UserAccessToken AS t WHERE t.validUntil < current_timestamp()';
        $query = $this->entityManager->createQuery($dql);
        $query->execute();
    }

    private function generateValue(): string
    {
        return bin2hex(string: random_bytes(length: self::VALUE_BYTES_LENGTH));
    }
}
