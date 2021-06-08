<?php


namespace App\Controller;


use App\Entity\Category\Category;
use App\Entity\Sport\Sport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PickCategoryController
 * @package App\Controller
 */
class PickCategoryController extends AbstractController
{
    /**
     * @Route("/{sportSlug}/allCategories", name="category")
     * @param string $sportSlug
     * @return Response
     */
    public function pickCategory(string $sportSlug): Response
    {
        $sport = $this->getDoctrine()->getRepository(Sport::class)->findOneBy(["slug" => $sportSlug]);

        $categories = $this->getDoctrine()->getRepository(Category::class)->findBy(["sport" => $sport]);

        return $this->render("pickCategory.html.twig", ["sport" => $sport, "categories" => $categories]);
    }
}