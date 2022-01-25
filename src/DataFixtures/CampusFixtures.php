<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $campus = new Campus();
        $campus->setNom("Nantes");
        $manager->persist($campus);

        $campus = new Campus();
        $campus->setNom("RENNES");
        $manager->persist($campus);

        $campus = new Campus();
        $campus->setNom("LAVAL");
        $manager->persist($campus);

        $manager->flush();
    }
}
