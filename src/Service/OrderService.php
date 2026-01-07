<?php

namespace App\Service;

use App\Repository\OrderRepository;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * Service for retrieving order history.
 */
readonly class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private Security $security,
    ) {
    }

    /**
     * Retrieves validated orders for current user
     *
     * @return array Array of validated orders
     */
    public function getOrdersForCurrentUser(): array
    {
        $user = $this->security->getUser();
        return $this->orderRepository->findOrdersByUser($user);
    }
}
