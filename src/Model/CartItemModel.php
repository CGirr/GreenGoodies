<?php

namespace App\Model;

/**
 * Data Transfer Object for cart line items.
 * Represents a product with quantity in the shopping cart.
 */
readonly class CartItemModel
{
    public function __construct(
        public int $id,
        public string $productName,
        public ?string $productImage,
        public ?string $productImageAlt,
        public int $quantity,
        public float $unitPrice,
        public float $totalPrice,
    ) {
    }
}
