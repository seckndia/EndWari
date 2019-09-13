<?php

namespace App\Repository;

use App\Entity\Compts;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Compts|null find($id, $lockMode = null, $lockVersion = null)
 * @method Compts|null findOneBy(array $criteria, array $orderBy = null)
 * @method Compts[]    findAll()
 * @method Compts[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ComptsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Compts::class);
    }

    // /**
    //  * @return Compts[] Returns an array of Compts objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Compts
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
