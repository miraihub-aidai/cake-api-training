<?php
declare(strict_types=1);

namespace App\Domain\UseCase;

use App\Domain\Interface\UsersInterface;

class GetUsers
{
    /**
     * GetUsers constructor
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
     * @return array<mixed> Decoded Users data
     */
    public function __invoke(): array
    {
        return $this->usersService->getUsers();
    }
}
