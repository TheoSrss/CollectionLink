<?php

namespace App\Repository;

use App\Entity\Picture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Picture>
 */
class PictureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Picture::class);
    }

    public function findByFileName(string $filename)
    {
        return $this->createQueryBuilder('p')
            ->where('p.name LIKE :filename')
            ->setParameter('filename', $filename . '.%')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
