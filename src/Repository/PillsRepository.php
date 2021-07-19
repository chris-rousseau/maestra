<?php

namespace App\Repository;

use App\Entity\Pills;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pills|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pills|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pills[]    findAll()
 * @method Pills[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PillsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pills::class);
    }

    // /**
    //  * @return Pills[] Returns an array of Pills objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Pills
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
