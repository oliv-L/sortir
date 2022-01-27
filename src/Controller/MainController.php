<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\SearchType;
use http\Cookie;
use App\Repository\SortieRepository;
use App\Repository\CampusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
                         CampusRepository $campusRepository,
                         Request $request): Response
    {

        $searchForm = $this->createForm(SearchType::class);
        $idCampus = null ;
        $search = null;
        $idOrganisateur = null;
        $dateMin = null;
        $dateMax = null;
        $etat = null;
        $searchForm->handleRequest($request);

       if ($searchForm->isSubmitted())
        {
            $data = $searchForm->getData();
            $idCampus = $searchForm->getData();

            $search = $request->query->get('search');
            $idOrganisateur = $request->query->get('id');
            $dateMin = null;
            $dateMax = null;
            $etat = null;
            $sorties = $sortieRespository->filtreSortie($idCampus, $search, $idOrganisateur, $dateMin, $dateMax, $etat);

                $this->redirectToRoute('sortie_create');
        }
        $sorties = $sortieRespository->filtreSortie($idCampus, $search, $idOrganisateur, $dateMin, $dateMax, $etat);
        $campus = $campusRepository->findAll();

        return $this->render('main/home.html.twig', ['sorties' => $sorties,
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
