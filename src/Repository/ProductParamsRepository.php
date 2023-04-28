<?php

namespace App\Repository;

use App\Entity\ProductParams;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductParams>
 *
 * @method ProductParams|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductParams|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductParams[]    findAll()
 * @method ProductParams[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductParamsRepository extends ServiceEntityRepository
{

    public function findColorsWithSearchQuery(
        ?string $search,
        ?string $title,
    ): array {
        $qb = $this->createQueryBuilder('productParams')
            ->innerJoin('productParams.product', 'product')
            ->andWhere('productParams.name = :color')
            ->setParameter('color', 'color')
            ->groupBy('productParams.value');
        if ($search) {
            $qb->andWhere('product.name LIKE :search OR product.description LIKE :search')
                ->setParameter('search', '%' . $search . '%');
        }

        if ($title) {
            $qb->andWhere('product.name LIKE :title')
                ->setParameter('title', '%' . $title . '%');
        }
        return $qb->getQuery()
            ->getResult();
    }

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductParams::class);
    }

    public function save(ProductParams $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(ProductParams $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return ProductParams[] Returns an array of ProductParams objects
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

//    public function findOneBySomeField($value): ?ProductParams
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
