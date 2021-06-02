<?php


namespace App\Entity\Sport;


use App\Entity\AbstractPrimaryEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Sport
 * @ORM\Entity(repositoryClass="App\Repository\Sport\SportRepository")
 * @package App\Entity
 */
class Sport extends AbstractPrimaryEntity
{

}