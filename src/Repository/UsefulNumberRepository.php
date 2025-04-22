<?php

namespace App\Repository;

use App\Entity\UsefulNumber;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UsefulNumber>
 */
class UsefulNumberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UsefulNumber::class);
    }

    public function search(?string $needle)
    {
        $qb = $this->createQueryBuilder('n');
        $qb->andWhere('n.name LIKE :param OR n.category LIKE :param OR n.phone_number LIKE :param')
            ->setParameter('param', '%' . $needle . '%')
            ->orderBy('n.category', 'DESC');

        return $qb->getQuery()->getResult();
    }
}
