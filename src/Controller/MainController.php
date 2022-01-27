<?php

namespace App\Controller;

use App\Entity\Participant;
use http\Cookie;
use App\Repository\SortieRepository;
use App\Repository\CampusRepository;
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
    public function home(SortieRepository $sortieRespository,
                         CampusRepository $campusRepository): Response
    {

        $sorties = $sortieRespository->findAll();
        $campus = $campusRepository->findAll();
        return $this->render('main/home.html.twig', ['sorties' => $sorties, 'campus'=>$campus]);

    }

   


   /**
     * @Route("/", name="accueil")
     */

    public function accueil(): Response
    {

        return $this->redirectToRoute("main_home");

    }

}
