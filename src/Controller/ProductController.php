<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\AddToCartType;
use App\Repository\ProductRepository;
use App\Service\CartService;
use App\Service\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductController extends AbstractController
{
    public function __construct(
        private readonly ProductService $productService,
        private readonly CartService $cartService,
    ) {
    }

    #[Route('/product/{id}', name: 'app_product', requirements: ['id' => '\d+'])]
    public function show(Product $product, Request $request): Response
    {
        $productModel = $this->productService->createProductModel($product);
        $cartInfo = $this->cartService->getCartInfoForProduct($product);

        $form = null;
        if ($this->getUser()) {
            $initialQuantity = $cartInfo['isInCart'] ? $cartInfo['quantity'] : 1;
            $form = $this->createForm(AddToCartType::class, ['quantity' => $initialQuantity]);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $quantity = $form->get('quantity')->getData();
                $this->cartService->updateProductQuantity($product, $quantity);

                return $this->redirectToRoute('app_product', ['id' => $product->getId()]);
            }
        }

        return $this->render('product/index.html.twig', [
            'product' => $productModel,
            'form' => $form,
            'isInCart' => $cartInfo['isInCart'],
        ]);
    }
}
