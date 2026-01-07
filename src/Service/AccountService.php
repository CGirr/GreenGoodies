<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

/**
 * Service for user account management.
 */
readonly class AccountService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    /**
     * Toggles API access for user
     *
     * @param User $user
     * @return void
     */
    public function toggleApiAccess(User $user): void
    {
        $user->setApiAccess(!$user->isApiAccess());
        $this->userRepository->save($user);
    }

    /**
     * Deletes a user account and all associated data
     *
     * @param User $user
     * @return void
     */
    public function deleteAccount(User $user): void
    {
        $this->userRepository->remove($user);
    }
}
