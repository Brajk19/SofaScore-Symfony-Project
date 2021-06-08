<?php


namespace App\Controller;


use App\Entity\Category\Category;
use App\Entity\Competition\Competition;
use App\Entity\Sport\Sport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PickCompetitionController extends AbstractController
{
    /**
     * @Route("/{sportSlug}/{categorySlug}/allCompetitions", name="competition")
     * @param string $sportSlug
     * @param string $categorySlug
     * @return Response
     */
    public function pickCompetition(string $sportSlug, string $categorySlug): Response
    {
        $sport = $this->getDoctrine()->getRepository(Sport::class)->findOneBy(["slug" => $sportSlug]);

        $category = $this->getDoctrine()->getRepository(Category::class)
            ->findOneBy(["slug" => $categorySlug, "sport" => $sport]);

        $competitions = $this->getDoctrine()->getRepository(Competition::class)->findBy(["category" => $category]);


        return $this->render("pickCompetition.html.twig", ["sport" => $sport, "category" => $category,
            "competitions" => $competitions]);
    }
}