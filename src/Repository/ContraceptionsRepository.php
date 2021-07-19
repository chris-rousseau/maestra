<?php

namespace App\Repository;

use App\Entity\Contraceptions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Contraceptions|null find($id, $lockMode = null, $lockVersion = null)
 * @method Contraceptions|null findOneBy(array $criteria, array $orderBy = null)
 * @method Contraceptions[]    findAll()
 * @method Contraceptions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ContraceptionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Contraceptions::class);
    }

    // /**
    //  * @return Contraceptions[] Returns an array of Contraceptions objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Contraceptions
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
