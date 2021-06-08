<?php

namespace App\Repository\Standings;

use App\Entity\Standings\Standings;
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


}