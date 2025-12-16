<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;

readonly class ProductService
{
    public function __construct(private ProductRepository $productRepository)
    {}

    public function getAllProducts(): array
    {
        return $this->productRepository->findAll();
    }

    public function getProduct(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }
}
