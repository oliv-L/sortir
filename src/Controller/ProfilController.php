<?php

namespace App\Controller;

use App\Entity\Participant;
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


           if ($profilForm->isSubmitted() && $profilForm->isValid())
           {
               $entityManager->persist($participant);
               $entityManager->flush();
               return $this->redirectToRoute('main_home');
           }


        return $this->render('profil/informationProfil.html.twig', [
            'profil' => $profilForm->createView(),
            'participant'=>$participant

        ]);
    }
}
