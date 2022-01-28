<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Repository\LieuRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LieuController extends AbstractController
{
    /**
     * @Route("/lieu/{nom}", name="lieu_route")
     */
    public function route($nom,
                          LieuRepository $lieuRepository,
                          Lieu $lieu): Response
    {
        $lieu = $lieuRepository->findOneBy($nom);
        return $this->render('lieu/index.html.twig', [
            'lieu' => $lieu
        ]);
    }
}
