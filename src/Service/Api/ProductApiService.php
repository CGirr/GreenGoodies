<?php

namespace App\Service\Api;

use App\Repository\ProductRepository;

readonly class ProductApiService
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {

    }

    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }
}
