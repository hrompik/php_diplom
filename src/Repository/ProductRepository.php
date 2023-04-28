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
    public function findIdWithAvgPrice(
        ?string $priceFrom = null,
        ?string $priceTo = null,
    ) {
        $qb = $this->createQueryBuilder('product');

        $qb->innerJoin('product.prices', 'prices')
            ->addSelect('prices');

        $qb->addSelect('AVG(prices.cost) as cost');
        if ($priceFrom) {
            $qb
                ->andHaving('cost >= :priceFrom')
                ->setParameter('priceFrom', $priceFrom);
        }

        if ($priceTo) {
            $qb
                ->andHaving('cost <= :priceTo')
                ->setParameter('priceTo', $priceTo);
        }
        $qb->groupBy('product.id');
        return $qb
            ->getQuery()
            ->getResult();
    }

    public function findAllWithSearchQuery(
        ?string $search,
        ?string $title,
        ?string $sorted = null,
        ?array $ids = [],
        ?string $sellerId = null,
        ?string $colors = '',
        ?string $crackedScreen = '',
        ?string $categoryId = ''
    ) {
        $qb = $this->createQueryBuilder('product')
            ->innerJoin('product.category', 'category')
            ->addSelect('category')
            ->innerJoin('product.prices', 'prices')
            ->addSelect('prices')
            ->innerJoin('prices.seller', 'seller')
            ->addSelect('seller')
            ->innerJoin('product.productImages', 'productImages')
            ->addSelect('productImages')
            ->innerJoin('product.productParams', 'productParamsColor')
            ->innerJoin('product.productParams', 'productParamsScreen')
            ->innerJoin('product.feedbacks', 'f')
            ->addSelect('COUNT(f.id) as feedbacks')
            ->addSelect('AVG(prices.cost) as cost');
        if ($search) {
            $qb->andWhere('product.name LIKE :search OR product.description LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }
        if ($title) {
            $qb->andWhere('product.name LIKE :title')
                ->setParameter('title', '%' . $title . '%');
        }
        if (count($ids) > 0) {
            $qb->andWhere('product.id IN (:ids)')
                ->setParameter(':ids', $ids);
        }
        if ($sellerId) {
            $qb->andWhere('seller.id = :sellerId')
                ->setParameter(':sellerId', $sellerId);
        }

        if ($colors) {
            $colors = explode(',', $colors);
            $qb->andWhere('productParamsColor.name = \'color\'')
                ->andWhere('productParamsColor.value in (:colors)')
                ->setParameter('colors', $colors);
        }

        if ($crackedScreen === 'no') {
            $qb->andWhere('productParamsScreen.name = \'crackedScreen\'')
                ->andWhere('productParamsScreen.value = :crackedScreen')
                ->setParameter('crackedScreen', $crackedScreen);
        }

        if ($categoryId) {
            $qb->andWhere('category.id = :categoryId')
                ->setParameter('categoryId', $categoryId);
        }
        $qb->groupBy('product.id');

        switch ($sorted) {
            case 'price':
                $qb->orderBy('cost', 'DESC');
                break;
            case '-price':
                $qb->orderBy('cost', 'ASC');
                break;
            case 'createdAt':
                $qb->orderBy('product.createdAt', 'DESC');
                break;
            case '-createdAt':
                $qb->orderBy('product.createdAt', 'ASC');
                break;
            case 'feedbacks':
                $qb->orderBy('feedbacks', 'DESC');
                break;
            case '-feedbacks':
                $qb->orderBy('feedbacks', 'ASC');
                break;
            case 'sold':
                $qb->orderBy('product.sold', 'DESC');
                break;
            default:
                $qb->orderBy('product.sold', 'ASC');
        }

        return $qb;
    }


    public
    function getProduct(
        int $id
    ): ?Product {
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

    public
    function getTop(): array
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


    public
    function __construct(
        ManagerRegistry $registry
    ) {
        parent::__construct($registry, Product::class);
    }

    public
    function save(
        Product $entity,
        bool $flush = false
    ): void {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public
    function remove(
        Product $entity,
        bool $flush = false
    ): void {
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
