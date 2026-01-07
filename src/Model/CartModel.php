<?php

namespace App\Model;

/**
 * Data Transfer Object for shopping cart.
 * Contains cart items and calculates total price.
 */
readonly class CartModel
{
    public function __construct(
        public array $items,
        public float $totalPrice,
    ) {
    }
}
