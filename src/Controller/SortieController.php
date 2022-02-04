<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\CancelSortieType;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/create/{id}", name="create")
     */
    public function create(EntityManagerInterface $entityManager,
                           Request                $request,
                           EtatRepository         $etatRepository,
                           int                    $id = 0,
                           SortieRepository       $sortieRepository


    ): Response
    {

        $sortie = new Sortie();
        if ($id != 0) {
            $sortie = $sortieRepository->find($id);
        }
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            if ($sortieForm->get('save')->isClicked()) {
                $etat = $etatRepository->findOneBy(['libelle' => Etat::creee()]);
            } else {
                $etat = $etatRepository->findOneBy(['libelle' => Etat::ouverte()]);
            }


            $campus = $this->getUser()->getCampus();
            $sortie->setEtat($etat);
            $sortie->setCampus($campus);
            $sortie->setOrganisateurSortie($this->getUser());


            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'La sortie est maintenant disponible !');
            return $this->redirectToRoute('main_home');
        }
        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(), 'sortie' => $sortie
        ]);
    }

    /**
     * @Route("/cancel/{id}", name="cancel")
     */
    public function cancel(Sortie                 $sortie,
                           Request                $request,
                           EtatRepository         $etatRepository,
                           EntityManagerInterface $entityManager)
    {
        if (!$sortie) {
            throw $this->createNotFoundException('cette sortie n\'existe plus');
        }


        $cancelForm = $this->createForm(CancelSortieType::class, $sortie);
        $cancelForm->handleRequest($request);
        if ($cancelForm->isSubmitted() && $cancelForm->isValid()) {
            $sortie->setEtat($etatRepository->findOneBy(['libelle' => Etat::annulee()]));
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('succes', 'la sortie est annulée');
            return $this->redirectToRoute('main_home');
        }
        return $this->render('sortie/CancelSortie.html.twig', ['cancelForm' => $cancelForm->createView(), 'sortie' => $sortie]);


    }

    /**
     * @Route("/publier/{id}", name="publier")
     */
    public function publier(Sortie                 $sortie,
                            EntityManagerInterface $entityManager,
                            EtatRepository         $etatRepository)
    {
        if (!$sortie) {
            throw $this->createNotFoundException('cette sortie n\'existe plus');
        }
        $sortie->setEtat($etatRepository->findOneBy(['libelle' => Etat::ouverte()]));
        $entityManager->persist($sortie);
        $entityManager->flush();
        $this->addFlash('succes', 'la sortie est maintenant disponible');
        return $this->redirectToRoute('main_home');


    }

    /**
     * @Route("/desincription/{id}", name="desinscription")
     */
    public function desinscription(Sortie                 $sortie,
                                   EntityManagerInterface $entityManager)
    {
        $sortie->removeParticipant($this->getUser());
        $entityManager->flush();

        return $this->redirectToRoute('main_home');
    }


}
