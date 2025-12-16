<?php

namespace App\Controller;

use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    public function __construct(private OrderService $orderService)
    {}

    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $orders = $this->orderService->getOrdersForCurrentUser();
        return $this->render('account/index.html.twig', [
            'orders' => $orders,
        ]);
    }
}
