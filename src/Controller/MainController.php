<?php

namespace App\Controller;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Form\SearchType;
use App\Model\FiltreSortie;

use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use App\Repository\CampusRepository;

use App\Service\MiseAJourEtatSortie;
use ContainerFvamBba\getDoctrine_DatabaseDropCommandService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Date;
use function Sodium\add;


/**
 * @Route ("/main", name="main_")
 */
class MainController extends AbstractController
{
    //todo voir pr recuperer le formulaire

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
    public function inscription(EntityManagerInterface $em,
                                EtatRepository $er,
                                int $id): Response
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

            if (count($sortie->getParticipants()) >= $sortie->getNbInscriptionsMax())
            {
                $sortie->setEtat($er->findOneBy(['libelle'=>Etat::cloturee()]));
            }

            $em->persist($sortie);
            $em->flush();
            $this->addFlash('success', 'Votre inscription a bien été enregistrée ! ');
        } else {
            $this->addFlash('error', 'Impossible de s\'inscrire à l\'événement');
        }

        return $this->redirectToRoute('main_home');


    }
    /**
     * @Route("/main/desinscription/{id}", name="desinscription")
     */
    public function desinscription(EntityManagerInterface $entityManager,
                                   EtatRepository $er,
                                   int $id):Response
    {
        $sortie = $entityManager->getRepository(Sortie::class)->find($id);

        //Quel est l'état de la sortie : ouverte!
        $ouvert = $sortie->getEtat()->getLibelle() === Etat::ouverte();

        if ($sortie->getEtat()->getLibelle() === Etat::ouverte() ||$sortie->getEtat()->getLibelle() === Etat::cloturee() ) {
            //enlever le participant de la liste
            $sortie->removeParticipant($this->getUser());

            if (count($sortie->getParticipants()) < $sortie->getNbInscriptionsMax() && $sortie->getDateLimiteInscription() > new \DateTime('now'))
            {
                $sortie->setEtat($er->findOneBy(['libelle'=>Etat::ouverte()]));
            }
            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'Votre désistement à été enregistré');
        } else {
            $this->addFlash('error','Votre désistement n\'a pas pu être pris en compte' );
        }

        return $this->redirectToRoute('main_home');
    }

    /**
     * @Route("/maj", name="accueil")
     */

        public function accueil(SortieRepository $sortieRepository,
                                EtatRepository $etatRepository,
                                EntityManagerInterface $entityManager): Response
        {
            //$dateintervalle = new \DateInterval('P1M');
           // $dateFuture = $dateTime->add($dateintervalle);

            // définition des tableaux selon etat sortie
            $majSortie = $sortieRepository->MiseAJourEtat(
                $etatRepository->findOneBy(['libelle' => Etat::ouverte()]),
                $etatRepository->findOneBy(['libelle'=>Etat::cloturee()]),
                $etatRepository->findOneBy(['libelle'=>Etat::finie()]) );

            foreach ($majSortie as $sortie)
            {

                $inscription = $sortie->getDateLimiteInscription() < new \DateTime('now');
                $finie = $sortie->getDateHeureDebut()<new \DateTime('now');
                $archive = $sortie->getDateHeureDebut()->add(new \DateInterval('P30D')) < (new \DateTime('now'));
                //on vérifie si il est cloturé, passé ET archivé
                if($archive && $inscription && $finie)
                {
                    $sortie->setEtat($etatRepository->findOneBy(['libelle'=>Etat::archivee()]));
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                }
                //sinon est il cloturé ET passé?
                else if ($inscription && $finie)
                {
                    $sortie->setEtat($etatRepository->findOneBy(['libelle'=>Etat::finie()]));
                    $entityManager->persist($sortie);
                    $entityManager->flush();
                }
                //est il cloturé?
                else if ($inscription)
                        {
                            $sortie->setEtat($etatRepository->findOneBy(['libelle' => Etat::cloturee()]));
                            $entityManager->persist($sortie);
                            $entityManager->flush();
                        }

            }
            return $this->redirectToRoute("main_home");
        }


    /**
     * @Route ("/afficher/{id}", name="afficher")
     *
     */

    public function afficherSortie(SortieRepository $sortieRepository, $id)
        {

        $sortie = $sortieRepository->find($id);

        return $this->render('sortie/affichage.html.twig', ['sortie' => $sortie]);

    }


}
