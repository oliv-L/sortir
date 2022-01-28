<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Model\FiltreSortie;

use App\Repository\SortieRepository;
use App\Repository\CampusRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;


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
                         CampusRepository $campusRepository,
                         Request $request,
                         UserInterface $participant
                         ): Response
    {

        $filtreSortie = new FiltreSortie();
        $participant->getUserIdentifier();

        $filtreSortie->setCampus($participant->getCampus());

        $searchForm = $this->createForm(SearchType::class, $filtreSortie);
        $searchForm->handleRequest($request);
        if($filtreSortie->getCampus() === null)
        {
            $filtreSortie->setCampus($participant->getCampus());
        }
        $sorties = $sortieRespository->filtreSortie($filtreSortie, $participant);

       $campus = $campusRepository->findAll();

        return $this->render('main/home.html.twig', [
            'sorties' => $sorties,
            'campus'=>$campus
            ,'searchForm'=>$searchForm->createView()
        ]);

    }


   /**
     * @Route("/", name="accueil")
     */

    public function accueil(): Response
    {

        return $this->redirectToRoute("main_home");

    }

}
