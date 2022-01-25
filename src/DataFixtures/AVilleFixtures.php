<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AVilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for($i=1; $i<=3; $i++)
        {
            $ville = new Ville();
            $ville->setNom($faker->city());
            $ville->setCodePostal(55555);
            $manager->persist($ville);
        }

        $manager->flush();
    }
}
