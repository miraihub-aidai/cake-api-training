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
     * Get user by ID
     *
     * @param string $id User ID
     * @return array<mixed>|null User
     */
    public function getUsersById(string $id): ?array;

    /**
     * Post users
     *
     * @param array<string, mixed> $data User data
     * @return array<mixed> Added users
     * @throws \RuntimeException When unable to save user
     */
    public function postUsers(array $data): array;

    /**
     * Put users
     *
     * @param string $id User ID
     * @param array<string, mixed> $data User data
     * @return array<mixed> Updated users
     * @throws \RuntimeException When unable to update user
     */
    public function putUsers(string $id, array $data): array;

    /**
     * Delete users
     *
     * @param string $id User ID
     * @return bool Success
     * @throws \RuntimeException When unable to delete user
     */
    public function deleteUsers(string $id): bool;
}
