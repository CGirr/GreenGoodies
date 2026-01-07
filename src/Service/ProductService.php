<?php

namespace App\Service;

use App\Entity\Product;
use App\Model\MediaModel;
use App\Model\ProductModel;
use App\Repository\ProductRepository;

/**
 * Service for retrieving and transforming product data
 */
readonly class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {
    }

    /**
     * Retrieve all products as DTO
     *
     * @return array Array of product DTO
     */
    public function getAllProducts(): array
    {
        $products = $this->productRepository->findAll();

        return array_map(fn(Product $product) => $this->createProductModel($product), $products);
    }

    /**
     * Retrieves a product entity by ID
     *
     * @param int $id The product ID
     * @return Product|null The product entity or null
     */
    public function getProduct(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    /**
     * Retrieves a product as DTO by ID
     *
     * @param int $id The product ID
     * @return ProductModel|null The product DTO or null
     */
    public function getProductModel(int $id): ?ProductModel
    {
        $product = $this->productRepository->find($id);

        if ($product === null) {
            return null;
        }

        return $this->createProductModel($product);
    }

    /**
     * Transforms a Product entity into a ProductModel DTO
     *
     * @param Product $product
     * @return ProductModel
     */
    public function createProductModel(Product $product): ProductModel
    {
        $images = [];
        foreach ($product->getMedia() as $media) {
            $images[] = new MediaModel(
                link: $media->getLink(),
                alt: $media->getAlt(),
                type: $media->getType(),
            );
        }

        return new ProductModel(
            id: $product->getId(),
            name: $product->getName(),
            shortDescription: $product->getShortDescription(),
            fullDescription: $product->getFullDescription(),
            price: $product->getPrice(),
            images: $images,
        );
    }
}
