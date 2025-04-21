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

    public function findByReportVote(Report $report, mixed $value): int
    {
        return count($this->createQueryBuilder('v')
            ->andWhere('v.report = :report')
            ->andWhere('v.type = :value')
            ->setParameter('report', $report)
            ->setParameter('value', $value)
            ->getQuery()
            ->getResult());
    }

    public function findByUserVote(Report $report, ?User $user = null): mixed
    {
        $qb = $this->createQueryBuilder('v')->select('v.type');
        $qb->andWhere('v.user = :user')->andWhere('v.report = :report')
            ->setParameter('user', $user)->setParameter('report', $report);

        return $qb->getQuery()->getResult();
    }
}
