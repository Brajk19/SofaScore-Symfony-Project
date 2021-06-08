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

}