<?php

namespace App\Repository;

use App\Entity\Notification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Notification>
 */
class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notification::class);
    }

    public function findByUser($user)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.recipient = :recipient')
            ->andWhere('n.isRead = :isRead')
            ->setParameter('isRead', false)
            ->setParameter('recipient', $user)
            ->orderBy('n.id', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
