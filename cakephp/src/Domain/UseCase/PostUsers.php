<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\UsersInterface;

class PostUsers
{
    /**
     * postUsers constructor
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
     * @param array<string, mixed> $data User data
     * @return array<mixed> Added user data
     */
    public function __invoke(array $data): array
    {
        return $this->usersService->postUsers($data);
    }
}
