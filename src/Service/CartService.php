<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\OrderProduct;
use App\Entity\Product;
use App\Model\CartItemModel;
use App\Model\CartModel;
use App\Repository\OrderProductRepository;
use App\Repository\OrderRepository;
use Doctrine\ORM\Exception\ORMException;
use Symfony\Bundle\SecurityBundle\Security;

readonly class CartService
{
    public function __construct
    (
        private OrderRepository $orderRepository,
        private OrderProductRepository $orderProductRepository,
        private Security $security,
    ) {
    }

    public function getCart(): ?Order
    {
        $user = $this->security->getUser();

        if ($user === null) {
            return null;
        }

        return $this->orderRepository->findCartByUser($user);
    }

    public function getOrCreateCart(): Order
    {
        $user = $this->security->getUser();
        $cart = $this->orderRepository->findCartByUser($user);

        if ($cart === null) {
            $cart = new Order;
            $cart->setCustomer($user);
            $cart->setTotalPrice(0);

            $this->orderRepository->save($cart);
        }

        return $cart;
    }

    public function getCartInfoForProduct(Product $product): array
    {
        // Looks for the cart
        $cart = $this->getCart();
        if ($cart === null) {
            return ['quantity' => 0, 'isInCart' => false];
        }

        // Looks for product in the cart
        foreach ($cart->getOrderProducts() as $orderProduct) {
            if ($orderProduct->getProduct()->getId() === $product->getId()) {
                return [
                    'quantity' => $orderProduct->getQuantity(),
                    'isInCart' => true,
                ];
            }
        }

        // Product not found in cart
        return ['quantity' => 0, 'isInCart' => false];
    }

    /**
     * @throws ORMException
     */
    public function updateCartTotalPrice(Order $cart): void
    {
        $this->orderRepository->refresh($cart);

        $total = 0;
        foreach ($cart->getOrderProducts() as $orderProduct) {
            $total += $orderProduct->getUnitPrice() * $orderProduct->getQuantity();
        }
        $cart->setTotalPrice($total);
        $this->orderRepository->save($cart);
    }

    public function updateProductQuantity(Product $product, int $quantity): void
    {
        $cart = $this->getOrCreateCart();

        // Looks for product in cart
        foreach ($cart->getOrderProducts() as $orderProduct) {
            if ($orderProduct->getProduct()->getId() === $product->getId()) {
                if ($quantity === 0) {
                    // Removes from cart
                    $this->orderProductRepository->remove($orderProduct);
                } else {
                    // Updates quantity
                    $orderProduct->setQuantity($quantity);
                    $this->orderProductRepository->save($orderProduct);
                }
                $this->updateCartTotalPrice($cart);
                return;
            }
        }

        if ($quantity > 0) {
            $orderProduct = new OrderProduct;
            $orderProduct->setProduct($product);
            $orderProduct->setQuantity($quantity);
            $orderProduct->setUnitPrice($product->getPrice());
            $orderProduct->setParentOrder($cart);

            $this->orderProductRepository->save($orderProduct);
            $this->updateCartTotalPrice($cart);
        }
    }

    public function clearCart(): void
    {
        $cart = $this->getCart();

        if ($cart === null) {
            return;
        }

        foreach ($cart->getOrderProducts() as $orderProduct) {
            $this->orderProductRepository->remove($orderProduct);
        }

        $cart->setTotalPrice(0);
        $this->orderRepository->save($cart);
    }

    public function validateCart(): bool
    {
        $cart = $this->getCart();

        if ($cart === null || $cart->getOrderProducts()->isEmpty()) {
            return false;
        }

        $cart->setStatus('validated');
        $cart->setOrderDate(new \DateTimeImmutable());
        $cart->setOrderNumber($cart->getId());

        $this->orderRepository->save($cart);
        return true;
    }

    public function getCartModel(): ?CartModel
    {
        $cart = $this->getCart();

        if ($cart === null) {
            return null;
        }

        $items = [];
        foreach ($cart->getOrderProducts() as $orderProduct) {
            $product = $orderProduct->getProduct();
            $mainImage = $product->getMedia()->first() ?: null;

            $items[] = new CartItemModel(
                id: $orderProduct->getId(),
                productName: $product->getName(),
                productImage: $mainImage?->getLink(),
                productImageAlt: $mainImage?->getAlt(),
                quantity: $orderProduct->getQuantity(),
                unitPrice: $orderProduct->getUnitPrice(),
                totalPrice: $orderProduct->getQuantity() * $orderProduct->getUnitPrice(),
            );
        }

        return new CartModel(
            items: $items,
            totalPrice: $cart->getTotalPrice(),
        );
    }
}
