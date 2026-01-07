<?php

namespace App\Model;

/**
 * Data Transfer Object for Product entity.
 * Used to transfer product data to views without exposing entity directly.
 */
readonly class ProductModel
{
    public function __construct(
        public int $id,
        public string $name,
        public string $shortDescription,
        public string $fullDescription,
        public float $price,
        public array $images,
    ) {
    }

    public function getMainImage(): ?MediaModel
    {
        return $this->images[0] ?? null;
    }
}
