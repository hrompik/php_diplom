<?php

namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 *
 * @method Product|null find($id, $lockMode = null, $lockVersion = null)
 * @method Product|null findOneBy(array $criteria, array $orderBy = null)
 * @method Product[]    findAll()
 * @method Product[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductRepository extends ServiceEntityRepository
{

    public function getProduct(int $id): ?Product
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->addSelect('c')
            ->leftJoin('p.prices', 'prices')
            ->addSelect('prices')
            ->leftJoin('prices.seller', 'seller')
            ->addSelect('seller')
            ->leftJoin('p.productImages', 'i')
            ->addSelect('i')
            ->leftJoin('p.feedbacks', 'feedbacks')
            ->addSelect('feedbacks')
            ->leftJoin('feedbacks.createdBy', 'createdBy')
            ->addSelect('createdBy')
            ->where('p.id = :pId')
            ->orderBy('prices.cost', 'ASC')
            ->setParameter('pId', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getTop(): array
    {
        return $this->createQueryBuilder('p')
            ->leftJoin('p.category', 'c')
            ->addSelect('c')
            ->leftJoin('p.prices', 'prices')
            ->addSelect('prices')
            ->addSelect('AVG(prices.cost)', 'prices.cost')
            ->leftJoin('p.productImages', 'i')
            ->addSelect('i')
            ->orderBy('p.sort ASC, p.sold', 'ASC')
            ->groupBy('p.id')
            ->setMaxResults(8)
            ->getQuery()
            ->getResult();
    }


    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    public function save(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Product $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
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
}
