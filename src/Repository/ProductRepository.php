<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\OffsetPaginator;
use Doctrine\ORM\Tools\Pagination\Window;
use Doctrine\ORM\Tools\Pagination\WindowPage;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

//    /**
//     * @return Product[] Returns an array of Product objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Product
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    /**
     * Récupère les pièces dont le stock est faible.
     *
     * @return Product[]
     */
    public function findLowStock(int $threshold = 3): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.stock <= :threshold')
            ->setParameter('threshold', $threshold)
            ->orderBy('p.stock', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcule la valeur totale du stock au catalogue.
     */
    public function getCatalogValue(): float
    {
        $result = $this->createQueryBuilder('p')
            ->select('SUM(p.price * p.stock)')
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }

    /**
     * Récupère les produits paginés pour le catalogue public.
     *
     * @return WindowPage<Product>
     */
    public function findPaginated(int $page = 1, int $limit = 12): WindowPage
    {
        $qb = $this->createQueryBuilder('p')
            ->addSelect('img')
            ->leftJoin('p.productImages', 'img')
            ->orderBy('p.createdAt', 'DESC');

        $paginator = new OffsetPaginator(fetchJoinCollection: true);

        return $paginator->paginate(
            $qb,
            Window::fromPageNumberAndSize($page, $limit)
        );
    }
}
