<?php

namespace App\Repository;

use App\Entity\ReviewsPills;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReviewsPills|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewsPills|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewsPills[]    findAll()
 * @method ReviewsPills[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewsPillsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewsPills::class);
    }

    // /**
    //  * @return ReviewsPills[] Returns an array of ReviewsPills objects
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
    public function findOneBySomeField($value): ?ReviewsPills
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
