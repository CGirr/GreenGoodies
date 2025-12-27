<?php

namespace App\Service;

use App\Entity\Product;
use App\Model\MediaModel;
use App\Model\ProductModel;
use App\Repository\ProductRepository;

readonly class ProductService
{
    public function __construct(private ProductRepository $productRepository)
    {}

    public function getAllProducts(): array
    {
        $products = $this->productRepository->findAll();

        return array_map(fn(Product $product) => $this->createProductModel($product), $products);
    }

    public function getProduct(int $id): ?Product
    {
        return $this->productRepository->find($id);
    }

    public function getProductModel(int $id): ?ProductModel
    {
        $product = $this->productRepository->find($id);

        if ($product === null) {
            return null;
        }

        return $this->createProductModel($product);
    }

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
