<?php

namespace App\Repository;

use App\Entity\Neighborhood;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Neighborhood>
 */
class NeighborhoodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Neighborhood::class);
    }

    public function findNeighborhood(string $value, int $limit = 10): array
    {
        return $this->createQueryBuilder('n')
            ->where('n.name LIKE :val')
            ->setParameter('val', $value . '%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
