<?php

namespace App\Controller;

use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for cart management (view, validate, clear)
 */
final class CartController extends AbstractController
{
    public function __construct(
        private readonly CartService $cartService
    ) {

    }

    /**
     * Displays the current user's cart
     *
     * @return Response The rendered cart page
     */
    #[Route('/cart', name: 'app_cart')]
    public function index(): Response
    {
        $cart = $this->cartService->getCartModel();

        return $this->render('cart/index.html.twig', [
           'cart' => $cart,
        ]);
    }

    /**
     * Clears all items from the cart
     *
     * @return Response Redirect to cart page
     */
    #[Route('/cart/clear', name: 'app_cart_clear')]
    public function clearCart(): Response
    {
        $this->cartService->clearCart();

        return $this->redirectToRoute('app_cart');
    }

    /**
     * Validates the cart and creates an order
     *
     * @return Response Redirect to cart page with success/error message
     */
    #[Route('/cart/validate', name: 'app_cart_validate')]
    public function validateCart(): Response
    {
        if (!$this->cartService->validateCart()) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_cart');
        }

        $this->addFlash('success', 'Votre commande a bien été validée !');
        return $this->redirectToRoute('app_cart');
    }
}
