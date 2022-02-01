<?php

namespace App\Service;

use App\Entity\Etat;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;


class MiseAJourEtatSortie
{


    public function MiseAJourFerme(SortieRepository $sr,
                                   EtatRepository $er,
                                   EntityManagerInterface $entityManager)
    {

        $dateNow = new \DateTime('now');
        // verification status ouvert -> fermé selon la date limite inscription
        $majOuverte = $sr->MiseAJourEtat($er->findOneBy(['libelle' => Etat::ouverte()]));
        foreach ($majOuverte as $sortie) {


            $inscription = $sortie->getDateLimiteInscription() < $dateNow;

            if ($inscription) {

                $sortie->setEtat($er->findOneBy(['libelle' => Etat::cloturee()]));
                $entityManager->persist($sortie);
                $entityManager->flush();

            }

        }
    }

public function MiseAJourTermine(SortieRepository $sr,
                                 EtatRepository $er,
                                 EntityManagerInterface $entityManager)
{
    $dateNow = new \DateTime('now');
    // verification status fermé -> terminé selon la date de l'évènement
    $majCloture = $sr->MiseAJourEtat($er->findOneBy(['libelle'=>Etat::cloturee()]));

    foreach ($majCloture as $sortie)
    {
        $finie = $sortie->getDateHeureDebut()<$dateNow;
        if($finie)
        {

            $sortie->setEtat($er->findOneBy(['libelle'=>Etat::finie()]));
            $entityManager->persist($sortie);
            $entityManager->flush();

        }

    }
}

public function MiseAJourArchive(SortieRepository $sr,
                                 EtatRepository $er,
                                 EntityManagerInterface $entityManager)
{
    $dateNow = new \DateTime('now');
    $dateintervalle = new \DateInterval('P30D');
    $dateFuture = $dateNow->add($dateintervalle);
    // verification status terminé -> archivé selon la date de l'évènement
    $majFinie = $sr->MiseAJourEtat($er->findOneBy(['libelle'=>Etat::finie()]));
    foreach ($majFinie as $sortie)
    {


        $archive = $sortie->getDateHeureDebut() < $dateFuture;
        if($archive)
        {

            $sortie->setEtat($er->findOneBy(['libelle'=>Etat::archivee()]));
            $entityManager->persist($sortie);
            $entityManager->flush();

        }

    }
}

}