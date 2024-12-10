<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\UsersInterface;

class DeleteUser
{
    /**
     * DeleteUser constructor
     *
     * @param \App\Domain\Interface\UsersInterface $usersService
     */
    public function __construct(
        protected UsersInterface $usersService
    ) {
    }

    /**
     * invoke method
     *
     * @param string $id UserID
     * @return bool true: Success / false: Error
     */
    public function __invoke(string $id): bool
    {
        return $this->usersService->deleteUser($id);
    }
}
