<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\UsersInterface;

class PatchUser
{
    /**
     * PatchUser constructor
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
     * @param array<mixed> $data User Request Data
     * @return array<mixed> Decoded Users data
     */
    public function __invoke(string $id, array $data): array
    {
        return $this->usersService->patchUser($id, $data);
    }
}
