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

    public function findAllRegions(): array
    {
        return $this->createQueryBuilder('s')
            ->select('DISTINCT s.region')
            ->getQuery()
            ->getResult();
    }

    public function findStationsByRegion(string $region): array
    {
        return $this->createQueryBuilder('s')
            ->where('s.region = :region')
            ->setParameter('region', $region)
            ->orderBy('s.distributor', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function searchStations(?string $keyword, string $region = 'Analamanga', int $limit = 10): array
    {
        $qb = $this->createQueryBuilder('s')
            ->where('s.region = :region')
            ->setParameter('region', $region)
            ->orderBy('s.distributor', 'ASC');

        if ($keyword) {
            $qb->andWhere(
                $qb->expr()->orX(
                    's.commune LIKE :keyword',
                    's.district LIKE :keyword',
                    's.locality LIKE :keyword',
                    's.name LIKE :keyword',
                    's.distributor LIKE :keyword'
                )
            )
                ->setParameter('keyword', '%' . $keyword . '%');
        }

        return $qb->getQuery()
            ->setMaxResults($limit)
            ->getResult();
    }
}
