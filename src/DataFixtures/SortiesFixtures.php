<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Entity\ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortiesFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {


        $faker = \Faker\Factory::create('fr_FR');
        $campus = $manager->getRepository(Campus::class)->findAll();
        $etat = $manager->getRepository(Etat::class)->findAll();
        $participant = $manager->getRepository(Participant::class)->findAll();



        $lieu = $manager->getRepository(Lieu::class)->findAll();

       for( $j=1; $j<=5; $j++) {
           $sortie = new Sortie();
           $sortie->setNom($faker->text(25));
           $sortie->setDateHeureDebut(new \DateTime());
           $sortie->setDateLimiteInscription(new \DateTime());
           $sortie->setNbInscriptionsMax(random_int(4,20));
           $sortie->setDuree(90);
           $sortie->setEtat($faker->randomElement($etat));
           $sortie->setCampus($faker->randomElement($campus));
           $sortie->setLieu($faker->randomElement($lieu));
           $sortie->setInfosSortie($faker->text(150));
           $sortie->setOrganisateurSortie($faker->randomElement($participant));

           for($i=1; $i<=5; $i++)
           {

               $sortie->addParticipant($faker->randomElement($participant));
           }
           //for ($i = 1; $i <= 4;$i++) {
//
   //            $sortie->addParticipant($faker->randomElement($participant));
   //        };

           $manager->persist($sortie);
       }
        $manager->flush();

    }

        public function getDependencies() : array
            {
                return [CampusFixtures::class, EtatFixtures::class, ParticipantsFixtures::class];
            }

}
