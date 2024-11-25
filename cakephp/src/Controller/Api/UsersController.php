<?php
declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppController;
use App\Domain\UseCase\DeleteUsers;
use App\Domain\UseCase\GetUsers;
use App\Domain\UseCase\GetUsersById;
use App\Domain\UseCase\PostUsers;
use App\Domain\UseCase\PutUsers;
use Cake\Http\Exception\InternalErrorException;
use Cake\Http\Exception\NotFoundException;
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
     * Get User by ID Action
     *
     * @param \App\Domain\UseCase\GetUsersById $getUsersById
     * @param string $id User ID
     * @return \Cake\Http\Response JSON response
     * @throws \Cake\Http\Exception\NotFoundException When user not found
     * @throws \Cake\Http\Exception\InternalErrorException When JSON encoding fails
     */
    public function getUsersById(GetUsersById $getUsersById, string $id)
    {
        try {
            $user = $getUsersById($id);

            if (!$user) {
                throw new NotFoundException('User not found');
            }

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
     * @param \App\Domain\UseCase\PostUsers $postUsers
     * @return \Cake\Http\Response JSON User response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function postUsers(PostUsers $postUsers)
    {
        $user = $postUsers($this->request->getData());

        try {
            $jsonString = json_encode($user, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw new InternalErrorException('Unable to encode user to JSON');
        }
    }

    /**
     * Put User Action
     *
     * @param \App\Domain\UseCase\PutUsers $putUsers
     * @param string $id User ID
     * @return \Cake\Http\Response JSON User response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function putUsers(PutUsers $putUsers, string $id)
    {
        $user = $putUsers($id, $this->request->getData());

        try {
            $jsonString = json_encode($user, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw new InternalErrorException('Unable to encode user to JSON');
        }
    }

    /**
     * Delete User Action
     *
     * @param \App\Domain\UseCase\DeleteUsers $deleteUsers
     * @param string $id User ID
     * @return \Cake\Http\Response JSON response
     * @throws \Cake\Http\Exception\InternalErrorException
     */
    public function deleteUsers(DeleteUsers $deleteUsers, string $id)
    {
        $result = $deleteUsers($id);

        try {
            $jsonString = json_encode($result, JSON_THROW_ON_ERROR);

            return $this->response
                ->withType('application/json')
                ->withStringBody($jsonString);
        } catch (JsonException $e) {
            throw $e;
        }
    }
}
