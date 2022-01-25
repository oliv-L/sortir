<?php

namespace App\Controller;

use App\Entity\Participant;
use http\Cookie;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route ("/", name="main_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/main", name="home")
     *
     */
    public function home(): Response
    {
        return $this->render('main/home.html.twig');

    }


   /**
     * @Route("/", name="accueil")
     */

    public function acceuil(): Response
    {

        return $this->redirectToRoute("app_login");

    }

}
