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
            $queryBuilder->setParameter('campus', $filtreSortie->getCampus()->getId());
        }

        if($filtreSortie->getSearch())
        {
            $queryBuilder->andWhere('s.nom like :search');
            $queryBuilder->setParameter('search', '%'.$filtreSortie->getSearch().'%');
        }

        if($filtreSortie->getOrganisateur())
        {
            $queryBuilder->andWhere('s.organisateurSortie = :id');
            $queryBuilder->setParameter('id', $participant->getId());
        }

        if($filtreSortie->getInscrit())
        {
          $queryBuilder->andWhere(':id MEMBER OF s.participants');
           $queryBuilder->setParameter('id', $participant->getId());
        }

        if ($filtreSortie->getNonInscrit()) {
            //$queryBuilder->leftJoin('s.participants', 'p');
            $queryBuilder->andWhere(':id NOT MEMBER OF s.participants');
           $queryBuilder->setParameter('id', $participant->getId());
            /*$queryBuilder->innerJoin('s.participants', 'p')
               ->where('p.id  != :id')
               ->addSelect('p');
            $queryBuilder->setParameter(':id', $participant->getId());*/

        }
        if($filtreSortie->getDateMin() && $filtreSortie->getDateMax())
        {
            $queryBuilder->andWhere('s.dateHeureDebut between :dateMin and :dateMax');
            $queryBuilder->setParameter('dateMin', $filtreSortie->getDateMin());
            $queryBuilder->setParameter('dateMax', $filtreSortie->getDateMax());
        }

        if($filtreSortie->getSortiePassee()) {
           // $etat = new Etat();
            //$etat->setLibelle('Fermé');
            //On récupère les sorties en etat passée
            //13 correspond à l'id du libelle passée
            $queryBuilder->andWhere('s.etat = :etat');
            $queryBuilder->setParameter('etat', Etat::finie());
        }
        //todo définir la date du moment
        //todo filtrage par participant ou non

        $query = $queryBuilder->getQuery()->getResult();

        return $query;
    }

    public function MiseAJourEtat($etatOuvert, $etatFerme, $etatTermine)
    {
/*$dql = 'SELECT s FROM App\Entity\Sortie s
        WHERE s.etat.id = $etatOuvert->getId()
       OR s.etat.id = $etatFerme->getId()
        OR s.etat.id ='+ $etatTermine->getId();

        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery($dql);*/


        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->orWhere('s.etat=:etatOuvert or s.etat=:etatFerme or s.etat=:etatTermine');
        $queryBuilder->setParameter('etatOuvert',$etatOuvert->getId());
        $queryBuilder->setParameter('etatFerme', $etatFerme->getId());
        $queryBuilder->setParameter('etatTermine', $etatTermine->getId());

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
