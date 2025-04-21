<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function searchUser(string $needle): array
    {
        $query = $this->createQueryBuilder('u')
            ->where('u.fullName LIKE :search')
            ->orWhere('u.email LIKE :search')
            ->setParameter('search', '%' . $needle . '%')
            ->setMaxResults(10)
            ->getQuery();

        $users = $query->getResult();

        return array_map(fn(User $user) => [
            'id' => $user->getId(),
            'fullName' => $user->getFullName(),
            'email' => $user->getEmail(),
        ], $users);
    }
}
