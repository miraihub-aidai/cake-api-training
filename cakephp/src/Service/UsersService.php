<?php
declare(strict_types=1);

namespace App\Service;

use App\Domain\Interface\UsersInterface;
use App\Model\Table\UsersTable;
use Cake\ORM\Locator\LocatorAwareTrait;
use RuntimeException;

class UsersService implements UsersInterface
{
    use LocatorAwareTrait;

    /**
     * UsersService constructor
     *
     * @param \App\Model\Table\UsersTable $users
     */
    public function __construct(
        protected UsersTable $users
    ) {
    }

    /**
     * Get users
     *
     * @return array<mixed> Users
     */
    public function getUsers(): array
    {
        $users = $this->users->find()
            ->select(['id', 'email', 'created', 'modified'])
            ->all();

        return [
            'users' => $users->toArray(),
        ];
    }

    /**
     * Get user by ID
     *
     * @param string $id User ID
     * @return array<mixed>|null Users
     */
    public function getUsersById(string $id): ?array
    {
        $users = $this->users->find()
            ->select(['id', 'email', 'created', 'modified'])
            ->where(['id' => $id])
            ->first();

        return $users ? $users->toArray() : null;
    }

    /**
     * Post users
     *
     * @param array<string, mixed> $data User data
     * @return array<mixed> Added users
     * @throws \RuntimeException When unable to save user
     */
    public function postUsers(array $data): array
    {
        $users = $this->users->newEntity($data);
        if ($this->users->save($users)) {
            $result = $users->toArray();
            unset($result['password']);

            return $result;
        }
        throw new RuntimeException('Unable to save user');
    }

    /**
     * Put users
     *
     * @param string $id User ID
     * @param array<string, mixed> $data User data
     * @return array<mixed> Updated users
     * @throws \RuntimeException When unable to update user
     */
    public function putUsers(string $id, array $data): array
    {
        $users = $this->users->get($id);
        $users = $this->users->patchEntity($users, $data);
        if ($this->users->save($users)) {
            $result = $users->toArray();
            unset($result['password']);

            return $result;
        }
        throw new RuntimeException('Unable to update user');
    }

    /**
     * Delete users
     *
     * @param string $id User ID
     * @return bool Success
     * @throws \RuntimeException When unable to delete user
     */
    public function deleteUsers(string $id): bool
    {
        $user = $this->users->get($id);
        if ($this->users->delete($user)) {
            return true;
        }
        throw new RuntimeException('Unable to delete user');
    }
}
