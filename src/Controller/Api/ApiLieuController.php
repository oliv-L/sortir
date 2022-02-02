<?php

namespace App\Controller\Api;


use App\Entity\Lieu;
use App\Entity\Ville;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/api", name="api_")
 */

class ApiLieuController extends AbstractController
{
    /*
    /**
     * @Route("/lieu/{id}", name="lieu", methods={"GET"})
     *
    public function lieu(int $idVille,
                         LieuRepository $lieuRepository
                         ): Response
    {
        $lieux= $lieuRepository->findOneBy(['ville_id'=>$idVille]);
        return $this->json($lieux, Response::HTTP_OK);
    }*/

    /**
     * @Route("/lieu", name="lieu", methods={"GET"})
     */
    public function lieu(Request        $request,
                         LieuRepository $lieuRepository
                         ): Response
    {
        $idVille = $request->get('id');
        $lieux = $lieuRepository->getLieu($idVille);

        return $this->json($lieux, Response::HTTP_OK, [], ['groups' => 'listeLieux']);
    }

    /**
     * @Route("/adresse", name="adresse", methods={"GET"})
     */
    public function adresse(Request $request, LieuRepository $lieuRepository): Response
    {
        $idlieu = $request->get('id');
        $lieu = $lieuRepository->lieuCodePostal($idlieu);

        return $this->json($lieu, Response::HTTP_OK, [], ['groups' => 'lieu']);

    }
}




