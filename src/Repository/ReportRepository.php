<?php

namespace App\Repository;

use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Report>
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    public function findAll(int $limit = 10): array
    {
        return $this->findBy([], ['id' => 'DESC'], $limit);
    }

    public function findPaginated(int $limit = 10, int $page = 0): array
    {
        $query = $this->createQueryBuilder('r')
            ->orderBy('r.id', 'DESC')
            ->getQuery()
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit);

        return iterator_to_array(new Paginator($query));
    }

    public function search(?string $needle, int $limit = 10): array
    {
        if (!$needle) {
            return [];
        }

        return $this->createQueryBuilder('r')
            ->andWhere('r.lieu LIKE :term OR r.message LIKE :term OR r.type LIKE :term')
            ->setParameter('term', '%' . $needle . '%')
            ->orderBy('r.id', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
