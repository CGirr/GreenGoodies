<?php

namespace App\Controller\Api;

use App\Service\Api\ProductApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * API Controller for product endpoints.
 * Requires JWT authentication (configured in security.yaml).
 */
final class ProductApiController extends AbstractController
{
    public function __construct(
        private readonly ProductApiService $productApiService,
        private readonly SerializerInterface $serializer,
    ) {

    }


    /**
     * Returns all products as JSON
     * Uses serializer groups to control exposed fields
     *
     * @return JsonResponse
     * @throws ExceptionInterface
     */
    #[Route('/api/products', name: 'products', methods: ['GET'])]
    public function getProducts(): JsonResponse
    {
        $products = $this->productApiService->getAllProducts();

        $json = $this->serializer->serialize($products, 'json', ['groups' => 'product_read']);

        return new JsonResponse($json, 200, [], true);
    }
}
