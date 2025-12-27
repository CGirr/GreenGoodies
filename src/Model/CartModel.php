<?php

namespace App\Model;

readonly class CartModel
{
    public function __construct(
        public array $items,
        public float $totalPrice,
    ) {
    }
}
