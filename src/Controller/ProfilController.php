<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Entity\Participant;
use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfilController extends AbstractController
{
    /**
     * @Route("/profil/{id}", name="profil_information")
     */
    public function information($id,
                                ParticipantRepository $participantRepository,
                                Request $request,
                                EntityManagerInterface $entityManager): Response
    {
        $participant = $participantRepository->find($id);

        $profilForm = $this->createForm(ProfilType::class, $participant);
        $profilForm ->handleRequest($request);


       /* if(!$profilForm->isSubmitted())
        {
            return $this->redirectToRoute('main_home');
        }*/
          if ($profilForm->isSubmitted() && $profilForm->isValid())
           {
               $entityManager->persist($participant);
               $entityManager->flush();
               $this->addFlash('success', 'Votre modification a bien été enregistrée ! ');
               return $this->redirectToRoute('main_home');
           } else
           {
               $this->addFlash('error', 'Erreur lors de la modification, veuillez remplir les champs demandés');
           }


        return $this->render('profil/informationProfil.html.twig', [
            'profil' => $profilForm->createView(),
            'participant'=>$participant

        ]);
    }

    /**
     * @Route("/profilParticipant/{id}", name="profil_profilParticipant")
     */
    public function showProfil(ParticipantRepository $participantRepository, $id){
        $participant = $participantRepository->find($id);

       // $organisateur = $em->getRepository(Sortie::class)->findSortie($id);

        return $this->render("profil/profilParticipant.html.twig", [
            "participant"=>$participant

        ]);
    }
    /**
     * @Route("/profilListe", name="profil_liste")
     */
    public function listeParticipant(ParticipantRepository $participantRepository){
        $liste = $participantRepository->findAll();
        
        return $this->render("profil/profilListe.html.twig", [
            "liste"=>$liste

        ]);
    }

    /**
     * @Route("/profilSupprimer/{id}", name="profil_supprimer")
     */
    public function supprimerParticipant(Participant $participant, EntityManagerInterface $entityManager)
    {

        $entityManager->remove($participant);
        $entityManager->flush();
        $this->addFlash('success', 'Suppression réussi !');
        return $this->redirectToRoute('profil_liste');
    }

    /**
     * @Route("/profilDesactiver/{id}", name="profil_desactiver")
     */
    public function desactiverParticipant(Participant $participant, EntityManagerInterface $entityManager){
        // todo action boutton on passe setActif(0)
        $participant->setActif(0);
        $entityManager->persist($participant);
        $entityManager->flush();
        $this->addFlash('success', 'Desactivation Réussi !');
        return $this->redirectToRoute('profil_liste');
    }

    /**
     * @Route("/profilActiver/{id}", name="profil_activer")
     */
    public function activerParticipant(Participant $participant, EntityManagerInterface $entityManager){
        // todo action boutton on passe setActif(0)
        $participant->setActif(1);
        $entityManager->persist($participant);
        $entityManager->flush();
        $this->addFlash('success', 'Activation Réussi !');
        return $this->redirectToRoute('profil_liste');
    }
    
}
