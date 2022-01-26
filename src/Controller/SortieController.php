<?php

namespace App\Controller;

use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/create", name="create")
     */
    public function create( EntityManagerInterface $entityManager,
                            Request $request,
                            EtatRepository $etatRepository,
                            UserInterface $participant
                            ): Response
    {

       $sortie = new Sortie();
       $sortieForm = $this->createForm(SortieType::class, $sortie);
       $sortieForm->handleRequest($request);

       if ($sortieForm->isSubmitted() && $sortieForm->isValid())
       {

           if($sortieForm->get('save')->isClicked()) {
               $etat = $etatRepository->findOneBy(['libelle' => 'En creation']);
           }else{
               $etat = $etatRepository->findOneBy(['libelle' => 'Ouvert']);

           }

           $participant->getUserIdentifier();

           $campus = $participant->getCampus();
           $sortie->setEtat($etat);
           $sortie->setCampus($campus);
           $sortie->setOrganisateurSortie($participant);


           $entityManager->persist($sortie);
           $entityManager->flush();
           $this->addFlash('success', 'La sortie est maintenant disponible !');
           return $this->redirectToRoute('main_home');
       }
        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }
}
