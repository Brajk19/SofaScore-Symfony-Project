<?php

namespace App\Controller;

use App\Entity\Competitor\Team;
use App\Entity\Country\Country;
use App\Entity\Sport\Sport;
use App\Service\Helper\DummyDataHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use League\ISO3166;

/**
 * Class TestController
 * @Route ("test")
 * @package App\Controller
 */
class TestController extends AbstractController
{


    /**
     * @Route ("/1")
     * @return Response
     */
    public function test(): Response
    {
        $country = new Country();

        $country->setIsoAlpha2("HR");

        echo $country->getName();

        $sport = new Sport();
        $sport->setName("australian football");
        $sport->setId(82);

        $team = new Team();
        $team->setName("Super Duper Team");
        $team->setCountry(new Country("AQ"));
        $team->setSport($sport);
        print_r($team);



         // var_dump(TeamNameHelper::getRandomTeams(10));


        return $this->render("test.html.twig", ["title" => "test", "message" => $country->getName()]);
    }


}