<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/sortir/profil", name="sortir_profil")
     */
    public function modifierProfil(Request $request, EntityManagerInterface $entityManager): Response
    {
        $profil = new Participant();
        $profilForm = $this->createForm(ProfilType::class, $profil);
        $profilForm->handleRequest($request);
        if($profilForm->isSubmitted() && $profilForm->isValid()){
            $entityManager->persist($profil);
            $entityManager->flush();
        }
        return $this->render('sortir/profil.html.twig', [
            'profilForm' => $profilForm->createView()
        ]);
    }
}
