<?php

namespace App\Repository\Competitor;

use App\Entity\Competitor\Competitor;
use App\Entity\Sport\Sport;
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

    public function competitorCheck(string $name, Sport $sport): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.name = :val1')
            ->setParameter('val1', $name)
            ->andWhere('c.sport = :val2')
            ->setParameter('val2', $sport)
            ->getQuery()
            ->getResult();
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
