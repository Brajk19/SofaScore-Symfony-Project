<?php


namespace App\Controller;


use App\Entity\Category\Category;
use App\Entity\Competition\Competition;
use App\Entity\Season\Season;
use App\Entity\Sport\Sport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PickSeasonController extends AbstractController
{

    /**
     * @Route("/{sportSlug}/{categorySlug}/{competitionSlug}/allSeasons", name="season")
     * @param string $sportSlug
     * @param string $categorySlug
     * @param string $competitionSlug
     * @return Response
     */
    public function pickSeason(string $sportSlug, string $categorySlug, string $competitionSlug): Response
    {
        $sport = $this->getDoctrine()->getRepository(Sport::class)->findOneBy(["slug" => $sportSlug]);

        $category = $this->getDoctrine()->getRepository(Category::class)
            ->findOneBy(["slug" => $categorySlug, "sport" => $sport]);

        $competition = $this->getDoctrine()->getRepository(Competition::class)->findOneBy(["category" => $category,
            "slug" => $competitionSlug]);

        $seasons = $this->getDoctrine()->getRepository(Season::class)->findBy(["competition" => $competition]);

        return $this->render("pickSeason.html.twig", ["sport" => $sport, "category" => $category,
            "competition" => $competition, "seasons" => $seasons]);
    }
}