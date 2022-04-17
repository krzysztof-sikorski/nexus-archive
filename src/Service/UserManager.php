<?php

declare(strict_types=1);

namespace App\Service;

use App\Contract\Service\ClockInterface;
use App\Doctrine\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class UserManager
{
    public function __construct(
        private ClockInterface $clock,
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @param string $username
     * @param string $plaintextPassword
     * @param string[] $roles
     * @return User
     */
    public function create(
        string $username,
        string $plaintextPassword,
        array $roles
    ): User {
        $createdAt = $this->clock->getCurrentDateTime();

        $user = new User();
        $user->setCreatedAt(createdAt: $createdAt);
        $user->setLastModifiedAt(lastModifiedAt: $createdAt);
        $user->setUsername(username: $username);
        $user->setRoles(roles: $roles);
        $user->setEnabled(enabled: true);

        $hashedPassword = $this->passwordHasher->hashPassword(user: $user, plainPassword: $plaintextPassword);
        $user->setPassword(password: $hashedPassword);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
