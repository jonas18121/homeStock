<?php

namespace App\Repository;

use App\Entity\StorageSpace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StorageSpace|null find($id, $lockMode = null, $lockVersion = null)
 * @method StorageSpace|null findOneBy(array $criteria, array $orderBy = null)
 * @method StorageSpace[]    findAll()
 * @method StorageSpace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StorageSpaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StorageSpace::class);
    }

    // /**
    //  * @return StorageSpace[] Returns an array of StorageSpace objects
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
    public function findOneBySomeField($value): ?StorageSpace
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function find_All_storage()
    {
        return $this->createQueryBuilder('s')
            ->select('s, b, c')
            ->leftJoin('s.bookings', 'b')
            ->leftJoin('s.category', 'c')
            ->getQuery()
            ->getResult();
        ;
    }

    public function find_one_booking_in_storage($id)
    {
        return $this->createQueryBuilder('s')
            ->select('s, b')
            ->leftJoin('s.bookings', 'b')
            ->andWhere('b.id IN (:id)')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
        ;
    }

    public function find_one_storage($id)
    {
        return $this->createQueryBuilder('s')
            ->select('s, b, u, c')
            ->leftJoin('s.bookings', 'b')
            ->leftJoin('s.owner', 'u')
            ->leftJoin('s.category', 'c')
            ->andWhere('s.id IN (:id)')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();
        ;
    }

    public function countStorageSpace()
    {
        return $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

}
