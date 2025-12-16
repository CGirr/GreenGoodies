<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class CartService
{
    public function __construct
    (
        private OrderRepository $orderRepository,
        private Security $security,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function getOrCreateCart(): Order
    {
        $user = $this->security->getUser();
        $cart = $this->orderRepository->findCartByUser($user);

        if ($cart === null) {
            $cart = new Order;
            $cart->setCustomer($user);
            $cart->setTotalPrice(0);

            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        return $cart;
    }

    public function getCartInfoForProduct(Product $product): array
    {
        $user = $this->security->getUser();

        // There's no cart if the user isn't connected
        if ($user === null) {
            return ['quantity' => 0, 'isInCart' => false];
        }

        // Looks for the cart
        $cart = $this->orderRepository->findCartByUser($user);
        if ($cart === null) {
            return ['quantity' => 0, 'isInCart' => false];
        }

        // Looks for product in the cart
        foreach ($cart->getOrderItems() as $orderItem) {
            if ($orderItem->getProduct()->getId() === $product->getId()) {
                return [
                    'quantity' => $orderItem->getQuantity(),
                    'isInCart' => true,
                ];
            }
        }

        // Product not found in cart
        return ['quantity' => 0, 'isInCart' => false];
    }

    public function updateCartTotalPrice(Order $cart): void
    {
        $total = 0;
        foreach ($cart->getOrderItems() as $orderItem) {
            $total += $orderItem->getUnitPrice() * $orderItem->getQuantity();
        }
        $cart->setTotalPrice($total);
        $this->entityManager->flush();
    }

    public function updateProductQuantity(Product $product, int $quantity): void
    {
        $cart = $this->getOrCreateCart();

        // Looks for product in cart
        foreach ($cart->getOrderItems() as $orderItem) {
            if ($orderItem->getProduct()->getId() === $product->getId()) {
                if ($quantity === 0) {
                    // Removes from cart
                    $this->entityManager->remove($orderItem);
                } else {
                    // Updates quantity
                    $orderItem->setQuantity($quantity);
                }
                $this->entityManager->flush();
                $this->updateCartTotalPrice($cart);
                return;
            }
        }

        if ($quantity > 0) {
            $orderItem = new OrderItem;
            $orderItem->setProduct($product);
            $orderItem->setQuantity($quantity);
            $orderItem->setUnitPrice($product->getPrice());
            $orderItem->setParentOrder($cart);

            $this->entityManager->persist($orderItem);
            $this->entityManager->flush();
            $this->updateCartTotalPrice($cart);
        }
    }
}
