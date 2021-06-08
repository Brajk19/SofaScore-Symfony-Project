<?php


namespace App\Controller;


use App\Entity\Sport\Sport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PickSportController
 * @package App\Controller
 */
class PickSportController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function pickSport(): Response
    {
        $sports = $this->getDoctrine()->getRepository(Sport::class)->findAll();

        return $this->render("pickSport.html.twig", ["sports" => $sports]);
    }
}