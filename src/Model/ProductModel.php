<?php

namespace App\Model;

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
