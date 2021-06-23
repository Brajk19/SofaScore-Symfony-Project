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
        $parameters = $this->getParametres($sportSlug, $categorySlug, $competitionSlug, $seasonSlug, "total");
        $parameters["activeLink"] = "total";

        switch($sportSlug){
            case "football":
                return $this->render("standingsFootball.html.twig", $parameters);
            case "basketball":
                return $this->render("standingsBasketball.html.twig", $parameters);
        }
    }

    /**
     * @Route("/home", name="standingsHome")
     * @param string $sportSlug
     * @param string $categorySlug
     * @param string $competitionSlug
     * @param string $seasonSlug
     * @return Response
     */
    public function showStandingsHome(string $sportSlug, string $categorySlug, string $competitionSlug, string $seasonSlug): Response
    {
        $parameters = $this->getParametres($sportSlug, $categorySlug, $competitionSlug, $seasonSlug, "home");
        $parameters["activeLink"] = "home";

        switch($sportSlug){
            case "football":
                return $this->render("standingsFootball.html.twig", $parameters);
            case "basketball":
                return $this->render("standingsBasketball.html.twig", $parameters);
        }
    }

    /**
     * @Route("/away", name="standingsAway")
     * @param string $sportSlug
     * @param string $categorySlug
     * @param string $competitionSlug
     * @param string $seasonSlug
     * @return Response
     */
    public function showStandingsAway(string $sportSlug, string $categorySlug, string $competitionSlug, string $seasonSlug): Response
    {
        $parameters = $this->getParametres($sportSlug, $categorySlug, $competitionSlug, $seasonSlug, "away");
        $parameters["activeLink"] = "away";

        switch($sportSlug){
            case "football":
                return $this->render("standingsFootball.html.twig", $parameters);
            case "basketball":
                return $this->render("standingsBasketball.html.twig", $parameters);
        }
    }


    private function getParametres(string $sportSlug, string $categorySlug, string $competitionSlug, string $seasonSlug, string $type): array
    {
        $sport = $this->getDoctrine()->getRepository(Sport::class)->findOneBy(["slug" => $sportSlug]);

        $category = $this->getDoctrine()->getRepository(Category::class)
            ->findOneBy(["slug" => $categorySlug, "sport" => $sport]);

        $competition = $this->getDoctrine()->getRepository(Competition::class)->findOneBy(["category" => $category,
            "slug" => $competitionSlug]);

        $season = $this->getDoctrine()->getRepository(Season::class)->findOneBy(["competition" => $competition,
            "slug" => $seasonSlug]);

        $standingsTotal = $this->getDoctrine()->getRepository(Standings::class)->findOneBy(["season" => $season,
            "type" => $type]);

        $rows = $this->getDoctrine()->getRepository(StandingsRow::class)->findBy(["standings" => $standingsTotal]);

        SortFunctions::rsort($rows); //StandingsRow implements SGH/Comparable interface


        return ["season" => $season, "rows" => $rows, "sport" => $sport, "category" => $category, "competition" => $competition];
    }

}