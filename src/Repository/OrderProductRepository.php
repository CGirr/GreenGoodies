<?php

namespace App\Repository;

use App\Entity\OrderProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository for OrderProduct entity (cart line items
 *
 * @extends ServiceEntityRepository<OrderProduct>
 */
class OrderProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderProduct::class);
    }

    /**
     * Persists and flushes an order product
     *
     * @param OrderProduct $orderProduct
     * @return void
     */
    public function save(OrderProduct $orderProduct): void
    {
        $this->getEntityManager()->persist($orderProduct);
        $this->getEntityManager()->flush();
    }

    /**
     * Removes an order product from the database
     *
     * @param OrderProduct $orderProduct
     * @return void
     */
    public function remove(OrderProduct $orderProduct): void
    {
        $this->getEntityManager()->remove($orderProduct);
        $this->getEntityManager()->flush();
    }

    //    /**
    //     * @return OrderProduct[] Returns an array of OrderProduct objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('o.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?OrderProduct
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
