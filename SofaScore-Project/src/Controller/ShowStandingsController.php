<?php


namespace App\Controller;


use App\Entity\Category\Category;
use App\Entity\Competition\Competition;
use App\Entity\Season\Season;
use App\Entity\Sport\Sport;
use App\Entity\Standings\Standings;
use App\Entity\Standings\StandingsRow;
use SGH\Comparable\SortFunctions;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ShowStandingsController
 * @Route("/{sportSlug}/{categorySlug}/{competitionSlug}/{seasonSlug}")
 * @package App\Controller
 */
class ShowStandingsController extends AbstractController
{

    /**
     * @Route("/total", name="standingsTotal")
     * @param string $sportSlug
     * @param string $categorySlug
     * @param string $competitionSlug
     * @param string $seasonSlug
     * @return Response
     */
    public function showStandingsTotal(string $sportSlug, string $categorySlug, string $competitionSlug, string $seasonSlug): Response
    {
        $sport = $this->getDoctrine()->getRepository(Sport::class)->findOneBy(["slug" => $sportSlug]);

        $category = $this->getDoctrine()->getRepository(Category::class)
            ->findOneBy(["slug" => $categorySlug, "sport" => $sport]);

        $competition = $this->getDoctrine()->getRepository(Competition::class)->findOneBy(["category" => $category,
            "slug" => $competitionSlug]);

        $season = $this->getDoctrine()->getRepository(Season::class)->findOneBy(["competition" => $competition,
            "slug" => $seasonSlug]);

        $standingsTotal = $this->getDoctrine()->getRepository(Standings::class)->findOneBy(["season" => $season,
            "type" => "total"]);

        $rows = $this->getDoctrine()->getRepository(StandingsRow::class)->findBy(["standings" => $standingsTotal]);

        SortFunctions::rsort($rows); //StandingsRow implements SGH/Comparable interface


        $parameters = ["season" => $season, "rows" => $rows];

        switch($sportSlug){
            case "football":
                return $this->render("standingsFootball.html.twig", $parameters);
            case "basketball":
                return $this->render("standingsBasketball.html.twig", $parameters);
        }
    }

}