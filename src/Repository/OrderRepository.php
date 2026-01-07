<?php

namespace App\Repository;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 *  Repository for Order entity.
 *  Handles cart and order queries.
 *
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     *  Finds the active cart for a user
     *
     * @param User $user
     * @return Order|null
     */
    public function findCartByUser(User $user): ?Order
    {
        return $this->createQueryBuilder('o')
            ->where('o.customer = :user')
            ->andWhere('o.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'cart')
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     *  Finds all validated orders for a user
     *
     * @param User $user
     * @return array
     */
    public function findOrdersByUser(User $user): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.customer = :user')
            ->andWhere('o.status = :status')
            ->setParameter('user', $user)
            ->setParameter('status', 'validated')
            ->getQuery()
            ->getResult();
    }

    /**
     * Persists and flushes an order
     *
     * @param Order $order
     * @return void
     */
    public function save(Order $order): void
    {
        $this->getEntityManager()->persist($order);
        $this->getEntityManager()->flush();
    }

    /**
     * Refreshes an order from the database
     *
     * @throws ORMException
     */
    public function refresh(Order $order): void
    {
        $this->getEntityManager()->refresh($order);
    }

    //    /**
    //     * @return Order[] Returns an array of Order objects
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

    //    public function findOneBySomeField($value): ?Order
    //    {
    //        return $this->createQueryBuilder('o')
    //            ->andWhere('o.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
