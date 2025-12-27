<?php

namespace App\Model;

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
