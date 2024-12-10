<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\UsersInterface;

class GetUserById
{
    /**
     * GetUserById constructor
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
     * @return array<mixed> Decoded User data
     */
    public function __invoke(string $id): array
    {
        return $this->usersService->getUserById($id);
    }
}
