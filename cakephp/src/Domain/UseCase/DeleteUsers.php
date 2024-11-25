<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\UsersInterface;

class DeleteUsers
{
    /**
     * DeleteUsers constructor
     *
     * @param \App\Domain\Interface\UsersInterface $usersService
     */
    public function __construct(
        protected UsersInterface $usersService
    ) {
    }

    /**
     * Invoke method
     *
     * @param string $id User ID
     * @return bool Success
     */
    public function __invoke(string $id): bool
    {
        return $this->usersService->deleteUsers($id);
    }
}
