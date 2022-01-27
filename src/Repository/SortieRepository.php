<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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

    public function filtreSortie($idCampus,
                                 $search,
                                 $idOrganisateur,
                                 $dateMin,
                                 $dateMax,
                                 $etat)
    {

        $queryBuilder=$this->createQueryBuilder('s');
        if($idCampus != null)
        $queryBuilder->andWhere('s.campus = '.$idCampus);
        if($search != null)
        $queryBuilder->andWhere('s.nom islike = %'.$search.'%');
        if($idOrganisateur != null)
        $queryBuilder->andWhere('s.organisateurSortie = '.$idOrganisateur);
        if($dateMin != null && $dateMax != null)
        $queryBuilder->andWhere('s.dateHeureDebut between'.$dateMin.'and'.$dateMax);
        if($etat != null)
        $queryBuilder->andWhere('s.etat ='.$etat);

        //todo filtrage par participant ou non

        $query = $queryBuilder->getQuery()->getResult();

        return $query;
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
