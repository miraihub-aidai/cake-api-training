<?php
declare(strict_types=1);

namespace App\Domain\Interface;

interface UsersInterface
{
    /**
     * Get users
     *
     * @return array<mixed> Users
     */
    public function getUsers(): array;

    /**
     * Get userById
     *
     * @param string $id UserID
     * @return array<mixed> User
     */
    public function getUserById(string $id): array;

    /**
     * Create user
     *
     * @param array<mixed> $data UserData
     * @return array<mixed> User
     */
    public function createUser(array $data): array;

    /**
     * Patch user
     *
     * @param string $id UserID
     * @param array<mixed> $data UserData
     * @return array<mixed> User
     */
    public function patchUser(string $id, array $data): array;

    /**
     * Delete user
     *
     * @param string $id UserID
     * @return bool true: Success / false: Error
     */
    public function deleteUser(string $id): bool;
}
