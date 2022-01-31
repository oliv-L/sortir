<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Sortie;
use App\Form\SortieType;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/sortie", name="sortie_")
 */
class SortieController extends AbstractController
{
    /**
     * @Route("/create/{id}", name="create")
     */
    public function create(EntityManagerInterface $entityManager,
                           Request                $request,
                           EtatRepository         $etatRepository,
                           $id =0,
                           SortieRepository $sortieRepository


    ): Response
    {

        $sortie = new Sortie();
        if($id !=0)
        {
            $sortie = $sortieRepository->find($id);
        }
        $sortieForm = $this->createForm(SortieType::class, $sortie);
        $sortieForm->handleRequest($request);

        if ($sortieForm->isSubmitted() && $sortieForm->isValid()) {

            if ($sortieForm->get('save')->isClicked()) {
                $etat = $etatRepository->findOneBy(['libelle' => Etat::creee()]);

            } else {
                $etat = $etatRepository->findOneBy(['libelle' => Etat::ouverte()]);

            }


            $campus = $this->getUser()->getCampus();
            $sortie->setEtat($etat);
            $sortie->setCampus($campus);
            $sortie->setOrganisateurSortie($this->getUser());


            $entityManager->persist($sortie);
            $entityManager->flush();
            $this->addFlash('success', 'La sortie est maintenant disponible !');
            return $this->redirectToRoute('main_home');
        }
        return $this->render('sortie/create.html.twig', [
            'sortieForm' => $sortieForm->createView(),
        ]);
    }

    /**
     * @Route("/desincription/{id}", name="desinscription")
     */
    public function desinscription(Sortie $sortie,
                                   EntityManagerInterface $entityManager
    )
    {

        $sortie->removeParticipant($this->getUser());
        $entityManager->flush();

       /* $participants = $sortie->getParticipants();
        foreach ($participants as $p)
       {
            if($p->getid() == $this->getUser()->getid())
                $id = $p->getid();}
       // dd($id);
*/
        return $this->redirectToRoute('main_home');
    }
}
