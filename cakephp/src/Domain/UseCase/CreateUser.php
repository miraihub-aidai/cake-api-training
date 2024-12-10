<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\UsersInterface;

class CreateUser
{
    /**
     * CreateUser constructor
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
     * @param array<mixed> $data User Request Data
     * @return array<mixed> Decoded Users data
     */
    public function __invoke(array $data): array
    {
        return $this->usersService->createUser($data);
    }
}
