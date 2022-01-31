<?php

namespace App\Controller;


use App\Entity\Sortie;
use App\Form\ProfilType;
use App\Repository\ParticipantRepository;
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
}
