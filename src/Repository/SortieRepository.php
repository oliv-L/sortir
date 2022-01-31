<?php

namespace App\Repository;

use App\Entity\Etat;
use App\Entity\Participant;
use App\Entity\Sortie;
use App\Model\FiltreSortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;


/**
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function filtreSortie(FiltreSortie $filtreSortie, UserInterface $participant)
    {

        $queryBuilder=$this->createQueryBuilder('s');


            if($filtreSortie->getCampus())
        {
            $queryBuilder->andWhere('s.campus =:campus');
            $queryBuilder->setParameter(':campus', $filtreSortie->getCampus()->getId());
        }

        if($filtreSortie->getSearch())
        {
            $queryBuilder->andWhere('s.nom like :search');
            $queryBuilder->setParameter(':search', '%'.$filtreSortie->getSearch().'%');
        }

        if($filtreSortie->getOrganisateur())
        {
            $queryBuilder->andWhere('s.organisateurSortie = :organisateur');
            $queryBuilder->setParameter(':organisateur', $participant->getId());
        }

        if($filtreSortie->getDateMin() && $filtreSortie->getDateMax())
        {
            $queryBuilder->andWhere('s.dateHeureDebut between :dateMin and :dateMax');
            $queryBuilder->setParameter('dateMin', $filtreSortie->getDateMin());
            $queryBuilder->setParameter('dateMax', $filtreSortie->getDateMax());
        }

        if($filtreSortie->getSortiePassee()) {
            $etat = new Etat();
            $etat->setLibelle('Fermé');
            $queryBuilder->andWhere('s.etat = :etat ');
            $queryBuilder->setParameter('etat', $etat);
        }
        //todo définir la date du moment
        //todo filtrage par participant ou non

        $query = $queryBuilder->getQuery()->getResult();

        return $query;
    }

    public function MiseAJourEtat($etat)
    {

        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->andWhere('s.etat=:etat');
        $queryBuilder->setParameter('etat', $etat->getId());
/*
        $queryBuilder->andWhere('s.etat=:etatCloturee');
        $etat->setLibelle(Etat::cloturee());
        $queryBuilder->setParameter('etatCloturee', $etat);

        $queryBuilder->andWhere('s.etat=:etatFinie');
        $etat->setLibelle(Etat::finie());
        $queryBuilder->setParameter('etatFinie', $etat);
*/
       return $queryBuilder->getQuery()->getResult();
    }
 /*   public function findSortie($idOrganisateur){
        return $this->createQueryBuilder('e')
            ->andWhere('e.organisateur_sortie_id = :val')
            ->setParameter('val', $idOrganisateur)
            ->getQuery()
            ->getResult();
    }*/


    // /**
    //  * @return Sortie[] Returns an array of Sortie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sortie
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
