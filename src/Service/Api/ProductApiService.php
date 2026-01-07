<?php

namespace App\Service\Api;

use App\Repository\ProductRepository;

/**
 * Service for API product operations.
 * Returns raw entities for serialization with groups.
 */
readonly class ProductApiService
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {

    }

    /**
     * Retrieves all products for API response
     *
     * @return array
     */
    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }
}
