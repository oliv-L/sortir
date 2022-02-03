<?php

namespace App\Controller;

use App\Entity\Lieu;
use App\Entity\Ville;
use App\Form\LieuType;
use App\Repository\LieuRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/lieu", name="lieu_")
 */

class LieuController extends AbstractController
{
    /**
     * @Route("/", name="route")
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

    /**
     * @Route("/create/{id}", name="create")
     */

    public function create(int $id, Request $request, VilleRepository $villeRepository, EntityManagerInterface $entityManager)
    {
        $lieu = new Lieu();
        $ville = $villeRepository->find($id);
        $lieu->setVille($ville);
        $createLieuForm = $this->createForm(LieuType::class, $lieu);
        $createLieuForm->handleRequest($request);

        if ($createLieuForm->isSubmitted() && $createLieuForm->isValid())
        {
                $entityManager->persist($lieu);
                $entityManager->flush();
                return $this->redirectToRoute('sortie_create');
        }

        return $this->render('lieu/createLieu.html.twig', ['createLieuForm'=>$createLieuForm->createView(), 'lieu'=>$lieu]);
    }
}
