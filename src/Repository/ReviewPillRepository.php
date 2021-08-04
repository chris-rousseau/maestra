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

    // 

    public function findAllOrderedByStatus()
    {
        $qb = $this->createQueryBuilder('review');
        $qb->orderBy('review.status', 'ASC');
        $qb->orderBy('review.created_at', 'DESC');

        $qb->leftJoin('review.user', 'user');
        $qb->leftJoin('review.pill', 'pill');
        $qb->addSelect('user, pill');

        $query = $qb->getQuery();

        return $query->getResult();
    }

    public function findByStatus($status)
    {
        $qb = $this->createQueryBuilder('review');
        $qb->where('review.status = :status');

        $qb->setParameter(':status', $status);

        $query = $qb->getQuery();
        return $query->getResult();
    }


    public function findWithDetails($id)
    {
        $qb = $this->createQueryBuilder('review');
        $qb->where('review.id = :id');
        $qb->setParameter(':id', $id);

        $qb->leftJoin('review.user', 'user');
        $qb->leftJoin('review.pill', 'pill');
        $qb->addSelect('user, pill');

        $query = $qb->getQuery();

        return $query->getOneOrNullResult();
    }
}
