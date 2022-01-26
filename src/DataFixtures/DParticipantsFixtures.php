<?php

// installation du fixture
// composer req orm-fixtures --dev

// installation du faker
//composer require fakerphp/faker --dev

//lancer la creation du jeu
// php bin/console doctrine:fixtures:load
namespace App\DataFixtures;

use App\Entity\Campus;
use App\Entity\Participant;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class DParticipantsFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');
        $campus = $manager->getRepository(Campus::class)->findAll();

         $participant = new Participant();
         $participant->setNom("Bernier");
         $participant->getRoles();
         $participant->setPseudo("Romain");
         $participant->setActif(true);
         $participant->setEmail("romainBernier@sortir.com");
         $participant->setPrenom("Romain");
         $participant->setTelephone('0123456789');
        $participant->setCampus($faker->randomElement($campus));
         $participant->setAdministrateur(true);
         $password = $this->passwordHasher->hashPassword($participant, "admin");
         $participant->setPassword($password);
         $manager->persist($participant);

        $participant = new Participant();
        $participant->setNom("Pasquette");
        $participant->getRoles();
        $participant->setPseudo("Corentin");
        $participant->setActif(true);
        $participant->setEmail("corentinPasquette@sortir.com");
        $participant->setPrenom("Corentin");
        $participant->setTelephone('0123456789');
        $participant->setAdministrateur(true);
        $participant->setCampus($faker->randomElement($campus));
        $password = $this->passwordHasher->hashPassword($participant, "admin");
        $participant->setPassword($password);
        $manager->persist($participant);

        $participant = new Participant();
        $participant->setNom("Lepetit");
        $participant->getRoles();
        $participant->setPseudo("Olivier");
        $participant->setTelephone('0123456789');
        $participant->setCampus($faker->randomElement($campus));
        $participant->setActif(true);
        $participant->setEmail("olivierLepetit@sortir.com");
        $participant->setPrenom("Olivier");
        $participant->setAdministrateur(true);
        $password = $this->passwordHasher->hashPassword($participant, "admin");
        $participant->setPassword($password);
        $manager->persist($participant);



        for ($i=1; $i<10; $i++)
        {
            $participant = new Participant();
            $participant->setNom($faker->lastName());
            $participant->getRoles();
            $participant->setPseudo($faker->firstName());
            $participant->setActif(true);
            $participant->setCampus($faker->randomElement($campus));
            $participant->setEmail($faker->email());
            $participant->setPrenom($faker->firstName());
            $participant->setAdministrateur(false);
            $password = $this->passwordHasher->hashPassword($participant, "user");
            $participant->setPassword($password);
            $participant->setTelephone('01'.($i-1).'345678'.$i);
            $manager->persist($participant);
        }


        $manager->flush();
    }

   public function getDependencies() : array
    {
       return [CampusFixtures::class];
    }
}
