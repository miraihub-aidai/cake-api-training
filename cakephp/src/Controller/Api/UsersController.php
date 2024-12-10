<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Domain\UseCase\CreateUser;
use App\Domain\UseCase\DeleteUser;
use App\Domain\UseCase\GetUserById;
use App\Domain\UseCase\GetUsers;
use App\Domain\UseCase\PatchUser;
use JsonException;

/**
 * UsersController
 *
 * @property \App\Model\Table\UsersTable $Users
 * @package App\Controller
 */
class UsersController extends AppController
{
    /**
     * Controller initialize method
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * Get Users Action
     *
     * @param \App\Domain\UseCase\GetUsers $getUsers
     * @return \Cake\Http\Response JSON Users response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function getUsers(GetUsers $getUsers)
    {
        $users = $getUsers();

        try {
            $jsonString = json_encode($users, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw $e;
        }
    }

    /**
     * Get UserById Action
     *
     * @param \App\Domain\UseCase\GetUserById $getUserById
     * @return \Cake\Http\Response JSON Users response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function getUserById(GetUserById $getUserById)
    {
        $userId = $this->request->getParam('userId');
        $user = $getUserById($userId);

        try {
            $jsonString = json_encode($user, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw $e;
        }
    }

    /**
     * Post User Action
     *
     * @param \App\Domain\UseCase\CreateUser $createUser
     * @return \Cake\Http\Response JSON Users response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function postUsers(CreateUser $createUser)
    {
        $data = $this->request->getData();
        $user = $createUser($data);

        try {
            $jsonString = json_encode($user, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw $e;
        }
    }

    /**
     * Put User Action
     *
     * @param \App\Domain\UseCase\PatchUser $patchUser
     * @return \Cake\Http\Response JSON Users response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function putUsers(PatchUser $patchUser)
    {
        $userId = $this->request->getParam('userId');
        $data = $this->request->getData();
        $user = $patchUser($userId, $data);

        try {
            $jsonString = json_encode($user, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw $e;
        }
    }

    /**
     * Delete User Action
     *
     * @param \App\Domain\UseCase\DeleteUser $deleteUser
     * @return \Cake\Http\Response JSON Users response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function deleteUsers(DeleteUser $deleteUser)
    {
        $userId = $this->request->getParam('userId');
        $deleteUser($userId);

        return $this->response->withType('application/json');
    }
}
