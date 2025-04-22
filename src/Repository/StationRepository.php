<?php

namespace App\Repository;

use App\Entity\Station;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Station>
 */
class StationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Station::class);
    }

    public function findAllRegion()
    {
        $qb = $this->createQueryBuilder('s');
        $qb->select('s.region');

        return $qb->getQuery()->getResult();
    }

    public function findByRegion(string $region)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.region = :val')
            ->setParameter('val', $region)
            ->orderBy('s.distributor', 'ASC')
            ->getQuery()->getResult();
    }
}
