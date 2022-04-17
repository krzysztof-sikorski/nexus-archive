<?php

declare(strict_types=1);

namespace App\Doctrine\Repository;

use App\Doctrine\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

use function get_class;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct(registry: $registry, entityClass: User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(
                message: sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $user->setPassword(password: $newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    public function findByUsername(string $username): ?User
    {
        $queryBuilder = $this->createQueryBuilder(alias: 'u')
            ->andWhere('u.username = :username')
            ->setParameter(key: 'username', value: $username);

        $query = $queryBuilder->getQuery();

        return $query->getOneOrNullResult();
    }

    public function getTotalCount(): int
    {
        $queryBuilder = $this->createQueryBuilder(alias: 'u')
            ->select(select: 'COUNT(u)');

        $query = $queryBuilder->getQuery();

        return $query->getSingleScalarResult();
    }
}
