<?php

namespace App\Service;

use App\Repository\OrderRepository;
use Symfony\Bundle\SecurityBundle\Security;

readonly class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository,
        private Security $security,
    ) {
    }

    public function getOrdersForCurrentUser(): array
    {
        $user = $this->security->getUser();
        return $this->orderRepository->findOrdersByUser($user);
    }
}
