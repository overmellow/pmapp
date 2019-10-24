<?php

namespace App\Repository;

use App\Entity\TempTicket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TempTicket|null find($id, $lockMode = null, $lockVersion = null)
 * @method TempTicket|null findOneBy(array $criteria, array $orderBy = null)
 * @method TempTicket[]    findAll()
 * @method TempTicket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TempTicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TempTicket::class);
    }

    // /**
    //  * @return TempTicket[] Returns an array of TempTicket objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TempTicket
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
