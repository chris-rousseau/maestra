<?php

namespace App\Repository;

use App\Entity\Pill;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Pill|null find($id, $lockMode = null, $lockVersion = null)
 * @method Pill|null findOneBy(array $criteria, array $orderBy = null)
 * @method Pill[]    findAll()
 * @method Pill[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PillRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Pill::class);
    }

    // /**
    //  * @return Pill[] Returns an array of Pill objects
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
    public function findOneBySomeField($value): ?Pill
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

     /***
     * Method enabling to return a pill based on its name 
     * 
     * Query Builder
     */
    public function findSearchByName($name)
    {
        $qb = $this->createQueryBuilder('pill'); // SELECT * FROM pill
        $qb->where('pill.name LIKE :name'); // WHERE name LIKE %name%
        $qb->setParameter(':name', "%$name%");
        $query = $qb->getQuery();
        return $query->getResult();
    }


     /***
     * Method enabling to return a pill based on criterias
     * 
     * Query Builder
     */
    public function findSearchSortedBy($interruption, $reimbursed, $generation, $undesirable)
    {
        $qb = $this->createQueryBuilder('pill'); // SELECT * FROM pill
        $qb->where('pill.interruption = :interruption'); 
        $qb->andWhere('pill.reimbursed = :reimbursed'); 
        if ($generation !== 0) {
            $qb->andWhere('pill.generation = :generation');
        }
        $qb->orderBy('pill.' . $undesirable, 'ASC');

        $qb->setParameter(':interruption', $interruption);
        $qb->setParameter(':reimbursed', $reimbursed);
        if ($generation != 0) {
            $qb->setParameter(':generation', $generation);
        }
        
        $query = $qb->getQuery();
        return $query->getResult();
    }


}
