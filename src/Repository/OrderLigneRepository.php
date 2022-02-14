<?php

namespace App\Repository;

use App\Entity\OrderLigne;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderLigne|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderLigne|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderLigne[]    findAll()
 * @method OrderLigne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderLigneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderLigne::class);
    }

    // /**
    //  * @return OrderLigne[] Returns an array of OrderLigne objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderLigne
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
