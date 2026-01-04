<?php

namespace App\Controller;

use App\Service\AccountService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccountController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly AccountService $accountService,
        private readonly Security $security,
    ) {
    }

    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $orders = $this->orderService->getOrdersForCurrentUser();
        return $this->render('account/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    #[Route('/account/toggle-api', name: 'app_account_toggle_api')]
    public function toggleApi(): Response
    {
        $user = $this->getUser();
        $this->accountService->toggleApiAccess($user);

        return $this->redirectToRoute('app_account');
    }

    #[Route('/account/delete', name: 'app_account_delete')]
    public function deleteAccount(): Response
    {
        $user = $this->getUser();

        $this->security->logout(false);
        $this->accountService->deleteAccount($user);

        $this->addFlash('success', 'Votre compte a été supprimé');

        return $this->redirectToRoute('app_home');
    }
}
