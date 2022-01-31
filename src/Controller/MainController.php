<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SearchType;
use App\Model\FiltreSortie;

use App\Repository\SortieRepository;
use App\Repository\CampusRepository;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route ("/main", name="main_")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     *
     */
    public function home(SortieRepository $sortieRespository,
                         CampusRepository $campusRepository,
                         Request          $request

    ): Response
    {

        $filtreSortie = new FiltreSortie();
        $filtreSortie->setCampus($this->getUser()->getCampus());
        $searchForm = $this->createForm(SearchType::class, $filtreSortie);
        $searchForm->handleRequest($request);

      /* if ($searchForm->isSubmitted())
       {

       }
       //if ($filtreSortie->getCampus() === null) {
         //   $filtreSortie->setCampus($this->getUser()->getCampus());
       // }*/

        $sorties = $sortieRespository->filtreSortie($filtreSortie, $this->getUser());
        $campus = $campusRepository->findAll();
        return $this->render('main/home.html.twig', [
            'sorties' => $sorties,
            'campus' => $campus
            , 'searchForm' => $searchForm->createView()
        ]);

    }

    /**
     * @Route ("/main/sinscrire/{id}", name="sinscrire")
     *
     */
    public function inscription(EntityManagerInterface $em, int $id): Response
    {
        //au préalable charger la dépendance suivante => composer require nelmio/cors-bundle
        $sortie = $em->getRepository(Sortie::class)->find($id);
        if(!$sortie)
        {
            throw $this->createNotFoundException("Sortie non existante");
        }

        //Est ce que la sortie est publiée?
        $sortiePubliee = $sortie->getEtat()->getLibelle() === Etat::ouverte();

        //Est ce que les inscriptions sont ouvertes?
        $sortieOuverte = $sortie->getDateLimiteInscription() > new \DateTime('now');

        //Est ce qu'il reste de la place?
        $sortieRemplie = count($sortie->getParticipants()) < $sortie->getNbInscriptionsMax();

        $inscriptionDispo = $sortiePubliee && $sortieOuverte && $sortieRemplie;

        if ($inscriptionDispo) {
            $sortie->addParticipant($this->getUser());
            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Votre inscription a bien été enregistrée ! ');
        } else {
            $this->addFlash('error', 'Impossible de s\'inscrire à l\'événement');
        }

        return $this->redirectToRoute('main_home');

    }

    /**
     * @Route("/", name="accueil")
     */

        public function accueil(): Response
        {

            return $this->redirectToRoute("main_home");

        }
    /**
     * @Route ("/afficher/{id}", name="afficher")
     *
     */

    public function afficherSortie(SortieRepository $sortieRepository, $id){
        
        $sortie = $sortieRepository->find($id);

        return $this->render('sortie/affichage.html.twig', ['sortie' => $sortie]);

    }


}
