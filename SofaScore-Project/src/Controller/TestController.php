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

        return $this->render("pickSport.html.twig");
        //return $this->render("test.html.twig", ["title" => "test", "message" => $country->getName()]);
    }


    /**
     * @Route("/2/{ht}", name="test2")
     * @param string $ht
     */
    public function test2(string $ht): void
    {
        echo "uspia si brate $ht";

    }


}