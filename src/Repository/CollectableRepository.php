<?php

namespace App\Repository;

use App\Entity\Collectable;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Collectable>
 */
class CollectableRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Collectable::class);
    }

    public function getUserCollectables(User $user): iterable
    {
        return $this->createQueryBuilder('c')
            ->where('creator_id = :userId')
            ->setParameter('userId', $user->getId())
            ->getQuery()
            ->getResult();
    }
}
