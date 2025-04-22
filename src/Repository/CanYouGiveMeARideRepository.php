<?php

namespace App\Repository;

use App\Entity\CanYouGiveMeARide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CanYouGiveMeARide>
 */
class CanYouGiveMeARideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CanYouGiveMeARide::class);
    }
}
