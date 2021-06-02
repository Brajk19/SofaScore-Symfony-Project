<?php

namespace App\Repository\Standings;

use App\Entity\Standings\StandingsRow;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StandingsRow|null find($id, $lockMode = null, $lockVersion = null)
 * @method StandingsRow|null findOneBy(array $criteria, array $orderBy = null)
 * @method StandingsRow[]    findAll()
 * @method StandingsRow[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StandingsRowRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StandingsRow::class);
    }

    // /**
    //  * @return StandingsRow[] Returns an array of StandingsRow objects
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
    public function findOneBySomeField($value): ?StandingsRow
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
