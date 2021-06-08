<?php


namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Verot\Upload\Upload;

/**
 * Class SarumanController
 * @Route("/soYouHaveChosen")
 * @package App\Controller
 * Kinda useless but it's fun.
 */
class SarumanController extends AbstractController
{

    /**
     * @Route("/{text}", name="saruman")
     * @param string $text
     */
    public function SarumanQuote(string $text): void
    {
            //unslug
            $text = str_replace("-", " ", $text);
            $text = ucwords($text);

            $handle = new Upload("../public/images/saruman.jpg");
            $handle->image_text_direction = "h";
            $handle->image_text_color = "#FFFFFF";
            $handle->image_text_font = 2;
            $handle->image_text_size = 10;
            $handle->image_text_background = "#000000";
            $handle->image_text_x = 205;
            $handle->image_text_y = 145;
            $handle->image_text = $text;
            $handle->process("./");

            header("Content-type: image/jpeg");
            $image = imagecreatefromjpeg("./saruman.jpg");
            imagejpeg($image);
            unlink("./saruman.jpg");
    }

    /**
     * @Route("/test")
     */
    public function test(): void
    {

    }

}