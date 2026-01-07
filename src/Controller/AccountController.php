<?php

namespace App\Controller;

use App\Service\AccountService;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Controller for user account management
 */
final class AccountController extends AbstractController
{
    public function __construct(
        private readonly OrderService $orderService,
        private readonly AccountService $accountService,
        private readonly Security $security,
    ) {
    }

    /**
     * Displays the user's account page with order history
     *
     * @return Response The rendered account page
     */
    #[Route('/account', name: 'app_account')]
    public function index(): Response
    {
        $orders = $this->orderService->getOrdersForCurrentUser();
        return $this->render('account/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * Toggles API access for current user
     *
     * @return Response Redirect to account page
     */
    #[Route('/account/toggle-api', name: 'app_account_toggle_api')]
    public function toggleApi(): Response
    {
        $user = $this->getUser();
        $this->accountService->toggleApiAccess($user);

        return $this->redirectToRoute('app_account');
    }

    /**
     * Deletes the current user's account and logs them out
     *
     * @return Response Redirect to homepage with success message
     */
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
