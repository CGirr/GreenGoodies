<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductService
{
    public function __construct(private readonly ProductRepository $productRepository)
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
