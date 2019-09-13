<?php

namespace App\Repository;

use App\Entity\Envoie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Envoie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Envoie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Envoie[]    findAll()
 * @method Envoie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EnvoieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Envoie::class);
    }

    // /**
    //  * @return Envoie[] Returns an array of Envoie objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Envoie
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
