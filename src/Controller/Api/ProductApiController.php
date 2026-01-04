<?php

namespace App\Controller\Api;

use App\Service\Api\ProductApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class ProductApiController extends AbstractController
{
    public function __construct(
        private readonly ProductApiService $productApiService,
        private readonly SerializerInterface $serializer,
    ) {

    }

    #[Route('/api/products', name: 'products', methods: ['GET'])]
    public function getProducts()
    {
        $products = $this->productApiService->getAllProducts();

        $json = $this->serializer->serialize($products, 'json', ['groups' => 'product_read']);

        return new JsonResponse($json, 200, [], true);
    }
}
