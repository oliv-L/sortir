<?php

namespace App\Controller;

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
     * @Route ("/main/sinscrire/{id}", name="sinscrire")
     *
     */
    public function inscription(EntityManagerInterface $em, int $id): Response
    {

        $sortie = $em->getRepository(Sortie::class)->find($id);
        if(!$sortie)
        {//erreur404}
        //Est ce que la sortie est publiée?
        $sortiePubliee = $sortie->getStatus()->getLibelle() === Etat::ouverte();

        //Est ce que les inscriptions sont ouvertes?
        $sortieOuverte = $sortie->getDateLimiteInscription() > new \DateTime('now');

        $sortieRemplie = count($sortie->getParticipants())->//nombre total autorisé;

        $inscriptionDispo = $sortiePubliee && $sortieOuverte;

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

}
