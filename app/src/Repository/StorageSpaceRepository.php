<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Repository;

use App\Classe\SearchData;
use App\Entity\StorageSpace;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Bundle\PaginatorBundle\Pagination\SlidingPagination;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @method StorageSpace|null find($id, $lockMode = null, $lockVersion = null)
 * @method StorageSpace|null findOneBy(array $criteria, array $orderBy = null)
 * @method StorageSpace[]    findAll()
 * @method StorageSpace[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StorageSpaceRepository extends ServiceEntityRepository
{
    private PaginatorInterface $paginationInterface;

    public function __construct(
        ManagerRegistry $registry,
        PaginatorInterface $paginationInterface
    ) {
        parent::__construct($registry, StorageSpace::class);

        $this->paginationInterface = $paginationInterface;
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

    public function findAllStorage(int $page): SlidingPagination
    {
        /** @var array<int, StorageSpace> */
        $data = $this->createQueryBuilder('s')
            ->select('s, b, c')
            ->leftJoin('s.bookings', 'b')
            ->leftJoin('s.category', 'c')
            ->getQuery()
            ->getResult();

        /** @var SlidingPagination */
        $pagination = $this->paginationInterface->paginate($data, $page, 10);

        if ($pagination instanceof SlidingPagination) {
            return $pagination;
        }
    }

    /**
     * @return StorageSpace
     */
    public function find_storage_space_from_booking_id(int $id)
    {
        /** @var StorageSpace */
        $storageSpace = $this->createQueryBuilder('s')
            ->select('s, b')
            ->leftJoin('s.bookings', 'b')
            ->andWhere('b.id IN (:id)')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();

        return $storageSpace;
    }

    /**
     * @return StorageSpace
     */
    public function find_one_storage(int $id)
    {
        /** @var StorageSpace */
        $storageSpace = $this->createQueryBuilder('s')
            ->select('s, b, u, c')
            ->leftJoin('s.bookings', 'b')
            ->leftJoin('s.owner', 'u')
            ->leftJoin('s.category', 'c')
            ->andWhere('s.id IN (:id)')
            ->setParameter('id', $id)
            ->getQuery()
            ->getSingleResult();

        return $storageSpace;
    }

    public function countStorageSpace(): int
    {
        /** @var array<int, int> */
        $count = $this->createQueryBuilder('s')
            ->select('COUNT(s)')
            ->getQuery()
            ->getSingleResult()
        ;

        return (int) $count[1];
    }

    public function findBySearch(SearchData $searchData): SlidingPagination
    {
        /** @var QueryBuilder */
        $query = $this->createQueryBuilder('s')
            ->select('s, b, c')
            ->leftJoin('s.bookings', 'b')
            ->leftJoin('s.category', 'c')
            ->addOrderBy('s.created_at', 'DESC');

        if (!empty($searchData->getQuery())) {
            $query = $query
                // Si l'user a écrit le code postal d'un produit depuis l'input, on l'affiche
                ->orWhere('s.postalCode LIKE :searchPostalCode')
                ->setParameter('searchPostalCode', "%{$searchData->getQuery()}%") // La recherche est partielle donc,
                // si on ecrit "bon", on va afficher tous les produits qui contiennent "bon"

                // Si l'user a écrit la ville d'un produit depuis l'input, on l'affiche
                ->orWhere('s.city LIKE :searchCity')
                ->setParameter('searchCity', "%{$searchData->getQuery()}%")

                // Créer un deuxieme input pour chercher uniquement le prix
                // Si l'user a écrit le prix de s.priceByMonth est egale ou plus petit que searchPriceByMonth depuis l'input, on l'affiche
                // ->orWhere('s.priceByMonth <= :searchPriceByMonth')
                // ->setParameter('searchPriceByMonth', (int) $searchData->getQuery() * 100)
            ;
        }

        /** @var array<int, StorageSpace> */
        $data = $query
            ->getQuery()
            ->getResult();

        /** @var SlidingPagination */
        $pagination = $this->paginationInterface->paginate($data, $searchData->getPage(), 10);

        if ($pagination instanceof SlidingPagination) {
            return $pagination;
        }
    }
}
