<?php

namespace App\Repository\Match;

use App\Entity\Match\AbstractMatch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AbstractMatch|null find($id, $lockMode = null, $lockVersion = null)
 * @method AbstractMatch|null findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractMatch[]    findAll()
 * @method AbstractMatch[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatchRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractMatch::class);
    }

    // /**
    //  * @return AbstractMatch[] Returns an array of AbstractMatch objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AbstractMatch
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
