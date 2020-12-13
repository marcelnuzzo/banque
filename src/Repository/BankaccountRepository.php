<?php

namespace App\Repository;

use App\Entity\Bankaccount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Bankaccount|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bankaccount|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bankaccount[]    findAll()
 * @method Bankaccount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BankaccountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Bankaccount::class);
    }

    // /**
    //  * @return Bankaccount[] Returns an array of Bankaccount objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Bankaccount
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}