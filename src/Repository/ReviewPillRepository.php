<?php

namespace App\Repository;

use App\Entity\ReviewPill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReviewPill|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewPill|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewPill[]    findAll()
 * @method ReviewPill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewPillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewPill::class);
    }

    // /**
    //  * @return ReviewPill[] Returns an array of ReviewPill objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReviewPill
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
