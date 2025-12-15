<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    public function __construct(private ProductService $productService)
    {}

    #[Route('/product/{id}', name: 'app_product')]
    public function show(int $id): Response
    {
        $product = $this->productService->getProduct($id);
        if ($product === null) {
            throw $this->createNotFoundException('Produit non trouvé');
        }

        return $this->render('product/index.html.twig', [
           'product' => $product,
        ]);
    }
}
