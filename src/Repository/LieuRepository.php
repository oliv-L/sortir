<?php

namespace App\Repository;

use App\Entity\Lieu;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Lieu|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lieu|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lieu[]    findAll()
 * @method Lieu[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LieuRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lieu::class);
    }

    public function getLieu(int $id)
    {
        $queryBuilder=$this->createQueryBuilder('l');
        $queryBuilder->andWhere('l.ville =:ville');
        $queryBuilder->setParameter(':ville', $id);
        return $queryBuilder->getQuery()->getResult();
    }

    public function lieuCodePostal(int $id)
    {
        $queryBuilder=$this->createQueryBuilder('l');
        $queryBuilder->innerJoin('l.ville', 'v')->addSelect('v');
        $queryBuilder->andWhere('l.id = :id');
        $queryBuilder->setParameter('id', $id);

        return $queryBuilder->getQuery()->getResult();

    }

    // /**
    //  * @return Lieu[] Returns an array of Lieu objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lieu
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
