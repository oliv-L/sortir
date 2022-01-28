<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
       $etat = new Etat();
       $etat->setLibelle('créée');
       $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('ouverte');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('en cours');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('cloturée');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('passée');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('annulée');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('archivée');
        $manager->persist($etat);

        $manager->flush();
    }
}
