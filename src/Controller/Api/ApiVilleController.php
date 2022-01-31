<?php

namespace App\Controller\Api;

use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route ("/api", name="api_")
 */
class ApiVilleController extends AbstractController
{
    /**
     * @Route("/listeVilles", name="listeVilles", methods={"GET"})
     */
    public function listeVilles(VilleRepository $villeRepository): Response
    {
        $villes=$villeRepository->findAll();
        return $this->json($villes, Response::HTTP_OK, [], ['groups'=>'listeVilles']);

    }
}
