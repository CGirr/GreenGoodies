<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;

readonly class AccountService
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function toggleApiAccess(User $user): void
    {
        $user->setApiAccess(!$user->isApiAccess());
        $this->userRepository->save($user);
    }

    public function deleteAccount(User $user): void
    {
        $this->userRepository->remove($user);
    }
}
