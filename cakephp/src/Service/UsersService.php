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
            ->select(['id', 'email', 'password', 'created', 'modified'])
            ->all();

        return [
            'users' => $users->toArray(),
        ];
    }

    /**
     * Get userById
     *
     * @param string $id UserID
     * @return array<mixed> User
     */
    public function getUserById(string $id): array
    {
        $user = $this->users->find()
            ->select(['id', 'email', 'password', 'created', 'modified'])
            ->where(['id =' => $id])
            ->firstOrFail();

        return $user->toArray();
    }

    /**
     * Create user
     *
     * @param array<mixed> $data UserData
     * @return array<mixed> User
     */
    public function createUser(array $data): array
    {
        $user = $this->users->newEmptyEntity();
        $user = $this->users->patchEntity($user, $data);
        if ($this->users->save($user)) {
            return $user->toArray();
        }
        throw new RuntimeException('User Create Error');
    }

    /**
     * Patch user
     *
     * @param string $id UserID
     * @param array<mixed> $data UserData
     * @return array<mixed> User
     */
    public function patchUser(string $id, array $data): array
    {
        $user = $this->users->get($id);
        if (empty($user->toArray())) {
            throw new RuntimeException('User Not Found Error');
        }
        $user = $this->users->patchEntity($user, $data);
        if ($this->users->save($user)) {
            return $user->toArray();
        }
        throw new RuntimeException('User Patch Error');
    }

    /**
     * Delete user
     *
     * @param string $id UserID
     * @return bool true: Success / false: Error
     */
    public function deleteUser(string $id): bool
    {
        $user = $this->users->get($id);
        if (empty($user->toArray())) {
            throw new RuntimeException('User Not Found Error');
        }
        if ($this->users->delete($user)) {
            return true;
        }
        throw new RuntimeException('User Delete Error');
    }
}
