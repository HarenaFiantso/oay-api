<?php

namespace App\Repository;

use App\Entity\ZaMbaHoento;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ZaMbaHoento>
 */
class ZaMbaHoentoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ZaMbaHoento::class);
    }
}
