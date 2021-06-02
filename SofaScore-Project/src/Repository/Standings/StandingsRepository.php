<?php

namespace App\Repository\Standings;

use App\Entity\Standings\Standings;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Standings|null find($id, $lockMode = null, $lockVersion = null)
 * @method Standings|null findOneBy(array $criteria, array $orderBy = null)
 * @method Standings[]    findAll()
 * @method Standings[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StandingsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Standings::class);
    }

    // /**
    //  * @return Standings[] Returns an array of Standings objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Standings
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
