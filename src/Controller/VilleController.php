<?php

namespace App\Controller;
use App\Entity\Ville;
use App\Form\VilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VilleController extends AbstractController
{
    /**
     * @Route("/ville", name="ville")
     */
    public function index(): Response
    {
        return $this->render('ville/index.html.twig', [
            'controller_name' => 'VilleController',
        ]);
    }

    /**
     * @Route("/create", name="ville_create")
     */

    public function create(Request $request, EntityManagerInterface $entityManager)
    {
        $ville = new Ville();
        $createVilleForm = $this->createForm(VilleType::class, $ville);
         $createVilleForm->handleRequest($request);

        if ($createVilleForm->isSubmitted() && $createVilleForm->isValid())
        {
            $entityManager->persist($ville);
            $entityManager->flush();
            return $this->redirectToRoute('sortie_create');
        }

        return $this->render('ville/createVille.html.twig', ['createVilleForm'=>$createVilleForm->createView()]);
    }
}
