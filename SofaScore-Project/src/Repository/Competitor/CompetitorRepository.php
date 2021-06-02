<?php

namespace App\Repository\Competitor;

use App\Entity\Competitor\Competitor;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Competitor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competitor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competitor[]    findAll()
 * @method Competitor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetitorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competitor::class);
    }

    // /**
    //  * @return Competitor[] Returns an array of Competitor objects
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
    public function findOneBySomeField($value): ?Competitor
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
