<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voting>
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function searchUser(string $needle): array
    {
        $data = $this->createQueryBuilder('u')
            ->andWhere('u.fullName = :fullName')
            ->andWhere('u.email = :email')
            ->setParameter('name', '%' . $needle . '%')
            ->setParameter('email', '%' . $needle . '%')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $lists = [];

        foreach ($data as $key => $item) {
            $lists[$key]['fullName'] = $item->getFullName();
            $lists[$key]['email'] = $item->getEmail();
            $lists[$key]['id'] = $item->getId();
        }

        return $lists;
    }
}
