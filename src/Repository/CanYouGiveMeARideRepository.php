<?php

namespace App\Repository;

use App\Entity\CanYouGiveMeARide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use InvalidArgumentException;

class CanYouGiveMeARideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CanYouGiveMeARide::class);
    }

    public function findPaginatedForWeb(int $page = 0, int $limit = 10): array
    {
        if ($page < 0 || $limit < 1) {
            throw new InvalidArgumentException('Page must be non-negative and limit must be positive');
        }

        $queryBuilder = $this->createQueryBuilder('c')
            ->where('c.departureLocation IS NOT NULL')
            ->andWhere('c.departureLocation != :empty')
            ->andWhere('c.arrivalLocation IS NOT NULL')
            ->andWhere('c.arrivalLocation != :empty')
            ->setParameter('empty', '')
            ->orderBy('c.id', 'DESC');

        $query = $queryBuilder->getQuery()
            ->setFirstResult($page * $limit)
            ->setMaxResults($limit);

        $paginator = new Paginator($query, true);

        return iterator_to_array($paginator);
    }

    public function findPaginated(?int $limit = 10): array
    {
        if ($limit !== null && $limit < 1) {
            throw new InvalidArgumentException('Limit must be positive or null');
        }

        return $this->findBy([], ['id' => 'DESC'], $limit);
    }
}