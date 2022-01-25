<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $villes = $manager->getRepository(Ville::class)->findAll();

        for($i=1; $i<=20; $i++)
        {
            $lieu = new Lieu();
            $lieu->setNom($faker->text(15));
            $lieu->setRue($faker->streetName());
            $lieu->setVille($faker->randomElement($villes));

            $manager->persist($lieu);
        }
        $manager->flush();
    }

    public function getDependencies() : array
    {
        return [AVilleFixtures::class];
    }

}
