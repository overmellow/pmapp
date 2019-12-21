<?php

namespace App\Repository;

use App\Entity\DrawEvent;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DrawEvent|null find($id, $lockMode = null, $lockVersion = null)
 * @method DrawEvent|null findOneBy(array $criteria, array $orderBy = null)
 * @method DrawEvent[]    findAll()
 * @method DrawEvent[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DrawEventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DrawEvent::class);
    }

    // /**
    //  * @return DrawEvent[] Returns an array of DrawEvent objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DrawEvent
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
