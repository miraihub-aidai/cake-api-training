<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\UsersInterface;

class GetUsers
{
    /**
     * @param \App\Domain\Interface\UsersInterface $usersService
     */
    public function __construct(
        private UsersInterface $usersService
    ) {
    }

    /**
     * Invoke
     *
     * @return array<string, mixed> Array of users
     */
    public function __invoke(): array
    {
        return $this->usersService->getUsers();
    }
}
