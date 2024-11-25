<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\UsersInterface;

class PutUsers
{
    /**
     * PutUsers constructor
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
     * @param array<string, mixed> $data User data
     * @return array<mixed> Edited user data
     */
    public function __invoke(string $id, array $data): array
    {
        return $this->usersService->putUsers($id, $data);
    }
}
