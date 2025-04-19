<?php

namespace App\Repository;

use App\Entity\Report;
use App\Entity\User;
use App\Entity\Voting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Voting>
 */
class VotingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Voting::class);
    }

    /**
     * @param Actualite $actu
     * @param mixed $value
     *
     * @return int Returns count an array of Voting objects
     */
    public function findByActualityVote(Report $actuality, mixed $value): int
    {
        return count($this->createQueryBuilder('v')
            ->andWhere('v.actuality = :actuality')
            ->andWhere('v.type = :value')
            ->setParameter('actuality', $actuality)
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult());
    }

    /**
     * @param Actualite $actualite
     * @param User|null $user
     *
     * @return mixed
     */
    public function findByUserVote(Report $actuality, ?User $user = null): mixed
    {
        $qb = $this->createQueryBuilder('v')->select('v.type');
        $qb->andWhere('v.user = :user')->andWhere('v.actuality = :actuality')
            ->setParameter('user', $user)->setParameter('actuality', $actuality);

        return $qb->getQuery()->getResult();
    }
}
