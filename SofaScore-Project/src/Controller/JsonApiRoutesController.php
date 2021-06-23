<?php


namespace App\Controller;


use App\Entity\Competition\Competition;
use App\Entity\Competitor\Competitor;
use App\Entity\Country\Country;
use App\Entity\Match\AbstractMatch;
use App\Entity\Season\Season;
use App\Entity\Sport\Sport;
use App\Entity\Standings\Standings;
use App\Entity\Standings\StandingsRow;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class JsonApiRoutesController
 * @IsGranted("ROLE_HAKERMAN")
 * @package App\Controller
 */
class JsonApiRoutesController extends AbstractController
{
    private static string $dateFormat = "d.m.Y H:i";

    /**
     * @Route("setMatch/{id}", methods={"POST"})
     * @param AbstractMatch $match
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return JsonResponse
     * @ParamConverter("match", class=AbstractMatch::class, options={"id": "id"})
     */
    public function setMatch(AbstractMatch $match, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        $json = json_decode($request->getContent(), true);

        $startTime = date_create_from_format(self::$dateFormat, $json["startTime"]);

        for($period = 0; $period < count($json["homeScore"]); $period++){
            $match->getHomeScore()->setScore($period + 1, $json["homeScore"][$period]);
            $match->getAwayScore()->setScore($period + 1, $json["awayScore"][$period]);
        }

        $match->setStatusCode($json["statusCode"]);
        $match->setStartTime($startTime);

        $entityManager->persist($match);
        $entityManager->flush();

        echo $match->getName() . " updated!\n";
        return new JsonResponse(json_encode(["status" => "OK"]),
            Response::HTTP_OK, [], true);
    }


    /**
     * @Route("changeCompetition/{id}", methods={"POST"})
     * @ParamConverter("competition", class=Competition::class, options={"id":"id"})
     * @param Competition $competition
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function changeCompetition(Competition $competition, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $json = json_decode($request->getContent(), true);

        if(isset($json["name"])){
            $competition->setName($json["name"]);
        }

        $entityManager->persist($competition);
        $entityManager->flush();

        return new JsonResponse(json_encode(["status" => "OK"]),
            Response::HTTP_OK, [], true);
    }

    /**
     * @Route("changeCompetitor/{id}", methods={"POST"})
     * @ParamConverter("competitor", class=Competitor::class, options={"id":"id"})
     * @param Competitor $competitor
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function changeCompetitor(Competitor $competitor, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $json = json_decode($request->getContent(), true);

        if(isset($json["name"])){
            $competitor->setName($json["name"]);
        }
        if(isset($json["isoAlpha2"])){
            $country = new Country($json["isoAlpha2"]);
            $competitor->setCountry($country);
        }

        $entityManager->persist($competitor);
        $entityManager->flush();

        return new JsonResponse(json_encode(["status" => "OK"]),
            Response::HTTP_OK, [], true);
    }


    /**
     * @Route("changeSeason/{id}", methods={"POST"})
     * @ParamConverter("season", class=Season::class, options={"id":"id"})
     * @param Season $season
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function changeSeason(Season $season, Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $json = json_decode($request->getContent(), true);

        if(isset($json["name"])){
            $season->setName($json["name"]);
        }
        if(isset($json["seasonStart"])){
            $seasonStart = date_create_from_format(self::$dateFormat, $json["seasonStart"]);
            $season->setSeasonStart($seasonStart);
        }
        if(isset($json["seasonEnd"])){
            $seasonEnd = date_create_from_format(self::$dateFormat, $json["seasonEnd"]);
            $season->setSeasonEnd($seasonEnd);
        }

        $entityManager->persist($season);
        $entityManager->flush();

        return new JsonResponse(json_encode(["status" => "OK"]),
            Response::HTTP_OK, [], true);
    }

    /**
     * @Route("recentMatches/", methods={"GET"})
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function recentMatches(EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        /**
         * @var Competitor[] $competitors
         */
        $competitors = $entityManager->getRepository(Competitor::class)->findAll();

        $json = [];

        foreach ($competitors as $competitor){
            $matches = $entityManager->getRepository(AbstractMatch::class)->findRecentMatches($competitor);
            $json[$competitor->getId()] = $matches;
        }

        return new JsonResponse($serializer->serialize($json, 'json', ['groups' => ['basic']]),
            Response::HTTP_OK, [], true);
    }

    /**
     * @Route("standings/{id}", methods={"GET"})
     * @ParamConverter("standings", class=Standings::class, options={"id":"id"})
     * @param Standings $standings
     * @param EntityManagerInterface $entityManager
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getStandingsRows(Standings $standings, EntityManagerInterface $entityManager, SerializerInterface $serializer): JsonResponse
    {
        //korisničko sučelje ne koristi ovu rutu jer je ono bilo napravljeno prije JSON API-ja
        $rows = $entityManager->getRepository(StandingsRow::class)->findBy(["standings" => $standings]);

        return new JsonResponse($serializer->serialize($rows, 'json', ['groups' => ['basic', 'extended']]),
            Response::HTTP_OK, [], true);
    }

    /**
     * @Route("standingsInfo/{id}", methods={"GET"})
     * @ParamConverter("standings", class=Standings::class, options={"id":"id"})
     * @param Standings $standings
     * @param SerializerInterface $serializer
     * @return JsonResponse
     */
    public function getStandingsInfo(Standings $standings, SerializerInterface $serializer): JsonResponse
    {
        return new JsonResponse($serializer->serialize($standings, 'json', ['groups' => ['basic', 'extended']]),
            Response::HTTP_OK, [], true);
    }
}