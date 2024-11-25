<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\UsersInterface;

class GetUsersById
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
     * @param string $id User ID
     * @return array<string, mixed>|null Array of user data or null if not found
     */
    public function __invoke(string $id): ?array
    {
        return $this->usersService->getUsersById($id);
    }
}
