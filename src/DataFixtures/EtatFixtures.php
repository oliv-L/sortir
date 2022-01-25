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
       $etat->setLibelle('En cours');
       $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Fermé');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('Ouvert');
        $manager->persist($etat);

        $etat = new Etat();
        $etat->setLibelle('En creation');
        $manager->persist($etat);

        $manager->flush();
    }
}
