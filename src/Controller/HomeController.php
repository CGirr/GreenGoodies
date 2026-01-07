<?php

namespace App\Controller;

use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for the homepage display.
 */
final class HomeController extends AbstractController
{
    public function __construct(
        private readonly ProductService  $productService,
    ) {
    }


    /**
     * Displays the homepage with all products.
     *
     * @return Response The rendered homepage
     */
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        $products = $this->productService->getAllProducts();
        return $this->render('home/index.html.twig', [
            'products' => $products
        ]);
    }
}
