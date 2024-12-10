<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\CreateUser;
use App\Domain\UseCase\DeleteUser;
use App\Domain\UseCase\GetUserById;
use App\Domain\UseCase\GetUsers;
use App\Domain\UseCase\PatchUser;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use JsonException;
use RuntimeException;

class UsersControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * @var array
     */
    protected array $mockedUsers = [
        [
            'id' => 1,
            'email' => 'TesMail1@sample.co.jp',
            'password' => 'user1',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ],
        [
            'id' => 2,
            'email' => 'TesMail2@sample.co.jp',
            'password' => 'user2',
            'created' => '2024-01-02 00:00:00',
            'modified' => '2024-01-02 00:00:00',
        ],
    ];

    /**
     * @var array
     */
    protected array $mockedUser = [
        [
            'id' => 1,
            'email' => 'TesMail1@sample.co.jp',
            'password' => 'user1',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ],
    ];

    /**
     * Setup before each test
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // ルーティングをリセット
        Router::reload();

        // ルーティングを設定
        Router::createRouteBuilder('/')
            ->setExtensions(['json'])
            ->scope('/api', function (RouteBuilder $builder) {
                $builder->setRouteClass(DashedRoute::class);
                // GET users
                $builder->connect('/users', [
                    'prefix' => 'Api',
                    'controller' => 'Users',
                    'action' => 'getUsers',
                    '_method' => 'GET',
                ])->setMethods(['GET']);
                // GET users By Id
                $builder->connect('/users/{userId}', [
                    'prefix' => 'Api',
                    'controller' => 'Users',
                    'action' => 'getUserById',
                    '_method' => 'GET',
                ])
                ->setPass(['userId'])
                ->setMethods(['GET']);
                // POST users
                $builder->connect('/users', [
                    'prefix' => 'Api',
                    'controller' => 'Users',
                    'action' => 'postUsers',
                    '_method' => 'POST',
                ])->setMethods(['POST']);
                // PUT users
                $builder->connect('/users/{userId}', [
                    'prefix' => 'Api',
                    'controller' => 'Users',
                    'action' => 'putUsers',
                    '_method' => 'PUT',
                ])
                ->setPass(['userId'])
                ->setMethods(['PUT']);
                // DELETE users
                $builder->connect('/users/{userId}', [
                    'prefix' => 'Api',
                    'controller' => 'Users',
                    'action' => 'deleteUsers',
                    '_method' => 'DELETE',
                ])
                ->setPass(['userId'])
                ->setMethods(['DELETE']);
            });

        // リクエストの設定
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        // CSRFチェックを無効化
        $this->disableErrorHandlerMiddleware();
    }

    /**
     * Test getUsers method
     *
     * @return void
     */
    public function testGetUsers(): void
    {
        // UsersServiceのモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $usersServiceMock->method('getUsers')
            ->willReturn(['users' => $this->mockedUsers]);

        // GetUsersのインスタンスを作成
        $getUsers = new GetUsers($usersServiceMock);

        // GetUsersをDIコンテナに登録
        $this->mockService(GetUsers::class, function () use ($getUsers) {
            return $getUsers;
        });

        // リクエストを実行
        $this->get('/api/users');

        // レスポンスを検証
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        // レスポンスボディを検証
        $expectedJson = json_encode(['users' => $this->mockedUsers]);
        $this->assertEquals($expectedJson, (string)$this->_response->getBody());
    }

    /**
     * Test getUsers method with empty result
     *
     * @return void
     */
    public function testGetUsersEmpty(): void
    {
        // UsersServiceのモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $usersServiceMock->method('getUsers')
            ->willReturn(['users' => []]);

        // GetUsersのインスタンスを作成
        $getUsers = new GetUsers($usersServiceMock);

        // GetUsersをDIコンテナに登録
        $this->mockService(GetUsers::class, function () use ($getUsers) {
            return $getUsers;
        });

        // リクエストを実行
        $this->get('/api/users');

        // レスポンスを検証
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        // レスポンスボディを検証
        $expectedJson = json_encode(['users' => []]);
        $this->assertEquals($expectedJson, (string)$this->_response->getBody());
    }

    /**
     * Test getUsers method with invalid JSON
     *
     * @return void
     */
    public function testGetUsersInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        // UsersServiceのモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // 無限ループする参照を作成
        $invalidData = [];
        $invalidData['recursive'] = &$invalidData;

        $usersServiceMock->method('getUsers')
            ->willReturn(['users' => $invalidData]);

        // GetUsersのインスタンスを作成
        $getUsers = new GetUsers($usersServiceMock);

        // GetUsersをDIコンテナに登録
        $this->mockService(GetUsers::class, function () use ($getUsers) {
            return $getUsers;
        });

        // リクエストを実行
        $this->get('/api/users');
    }

    /**
     * Test getUserById method
     *
     * @return void
     */
    public function testGetUserById(): void
    {
        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $usersServiceMock->method('getUserById')
            ->willReturn(['user' => $this->mockedUser]);

        // GetUserById のインスタンスを作成
        $getUserById = new GetUserById($usersServiceMock);

        // GetUserById をDIコンテナに登録
        $this->mockService(GetUserById::class, function () use ($getUserById) {
            return $getUserById;
        });

        // リクエストを実行
        $this->get('/api/users/1');

        // レスポンスを検証
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        // レスポンスボディを検証
        $expectedJson = json_encode(['user' => $this->mockedUser]);
        $this->assertEquals($expectedJson, (string)$this->_response->getBody());
    }

    /**
     * Test getUserById method with empty result
     *
     * @return void
     */
    public function testGetUserByIdEmpty(): void
    {
        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $usersServiceMock->method('getUserById')
            ->willReturn(['user' => []]);

        // GetUserById のインスタンスを作成
        $getUserById = new GetUserById($usersServiceMock);

        // GetUserById をDIコンテナに登録
        $this->mockService(GetUserById::class, function () use ($getUserById) {
            return $getUserById;
        });

        // リクエストを実行
        $this->get('/api/users/2');

        // レスポンスを検証
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        // レスポンスボディを検証
        $expectedJson = json_encode(['user' => []]);
        $this->assertEquals($expectedJson, (string)$this->_response->getBody());
    }

    /**
     * Test getAgetUserByIdrticles method with invalid JSON
     *
     * @return void
     */
    public function testGetUserByIdInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // 無限ループする参照を作成
        $invalidData = [];
        $invalidData['recursive'] = &$invalidData;

        $usersServiceMock->method('getUserById')
            ->willReturn(['user' => $invalidData]);

        // GetUserById のインスタンスを作成
        $getUserById = new GetUserById($usersServiceMock);

        // GetUserById をDIコンテナに登録
        $this->mockService(GetUserById::class, function () use ($getUserById) {
            return $getUserById;
        });

        // リクエストを実行
        $this->get('/api/users/3');
    }

    /**
     * Test postUsers method
     *
     * @return void
     */
    public function testPostUsers(): void
    {
        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $usersServiceMock->method('createUser')
            ->willReturn(['user' => $this->mockedUser]);

        // CreateUser のインスタンスを作成
        $createUser = new CreateUser($usersServiceMock);

        // CreateUser をDIコンテナに登録
        $this->mockService(CreateUser::class, function () use ($createUser) {
            return $createUser;
        });

        // リクエストを実行
        $this->post('/api/users', $this->mockedUser);

        // レスポンスを検証
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        // レスポンスボディを検証
        $expectedJson = json_encode(['user' => $this->mockedUser]);
        $this->assertEquals($expectedJson, (string)$this->_response->getBody());
    }

    /**
     * Test postUsers method with UseCase Error
     *
     * @return void
     */
    public function testPostUsersUseCaseError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('UseCase error occurred');

        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $usersServiceMock->method('createUser')
            ->willThrowException(new RuntimeException('UseCase error occurred'));

        // CreateUser のインスタンスを作成
        $createUser = new CreateUser($usersServiceMock);

        // CreateUser をDIコンテナに登録
        $this->mockService(CreateUser::class, function () use ($createUser) {
            return $createUser;
        });

        // リクエストを実行
        $this->post('/api/users', $this->mockedUser);
    }

    /**
     * Test postUsers method with invalid JSON
     *
     * @return void
     */
    public function testPostUsersInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // 無限ループする参照を作成
        $invalidData = [];
        $invalidData['recursive'] = &$invalidData;

        $usersServiceMock->method('createUser')
            ->willReturn(['user' => $invalidData]);

        // CreateUser のインスタンスを作成
        $createUser = new CreateUser($usersServiceMock);

        // CreateUser をDIコンテナに登録
        $this->mockService(CreateUser::class, function () use ($createUser) {
            return $createUser;
        });

        // リクエストを実行
        $this->post('/api/users', $this->mockedUser);
    }

    /**
     * Test putUsers method
     *
     * @return void
     */
    public function testPutUsers(): void
    {
        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $usersServiceMock->method('patchUser')
            ->willReturn(['user' => $this->mockedUser]);

        // PatchUser のインスタンスを作成
        $patchUser = new PatchUser($usersServiceMock);

        // PatchUser をDIコンテナに登録
        $this->mockService(PatchUser::class, function () use ($patchUser) {
            return $patchUser;
        });

        // リクエストを実行
        $this->put('/api/users/1', $this->mockedUser);

        // レスポンスを検証
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        // レスポンスボディを検証
        $expectedJson = json_encode(['user' => $this->mockedUser]);
        $this->assertEquals($expectedJson, (string)$this->_response->getBody());
    }

    /**
     * Test putUsers method with UseCase Error
     *
     * @return void
     */
    public function testPutUsersUseCaseError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('UseCase error occurred');

        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $usersServiceMock->method('patchUser')
            ->willThrowException(new RuntimeException('UseCase error occurred'));

        // PatchUser のインスタンスを作成
        $patchUser = new PatchUser($usersServiceMock);

        // PatchUser をDIコンテナに登録
        $this->mockService(PatchUser::class, function () use ($patchUser) {
            return $patchUser;
        });

        // リクエストを実行
        $this->put('/api/users/2', $this->mockedUser);
    }

    /**
     * Test putUsers method with invalid JSON
     *
     * @return void
     */
    public function testPutUsersInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // 無限ループする参照を作成
        $invalidData = [];
        $invalidData['recursive'] = &$invalidData;

        $usersServiceMock->method('patchUser')
            ->willReturn(['user' => $invalidData]);

        // PatchUser のインスタンスを作成
        $patchUser = new PatchUser($usersServiceMock);

        // PatchUser をDIコンテナに登録
        $this->mockService(PatchUser::class, function () use ($patchUser) {
            return $patchUser;
        });

        // リクエストを実行
        $this->put('/api/users/3', $this->mockedUser);
    }

    /**
     * Test deleteUsers method
     *
     * @return void
     */
    public function testDeleteUsers(): void
    {
        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $usersServiceMock->method('deleteUser')
            ->willReturn(true);

        // DeleteUser のインスタンスを作成
        $deleteUser = new DeleteUser($usersServiceMock);

        // DeleteUser をDIコンテナに登録
        $this->mockService(DeleteUser::class, function () use ($deleteUser) {
            return $deleteUser;
        });

        // リクエストを実行
        $this->delete('/api/users/1');

        // レスポンスを検証
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        // レスポンスボディを検証
        //$expectedJson = json_encode('');
        $this->assertEquals('', (string)$this->_response->getBody());
    }

    /**
     * Test deleteUsers method with UseCase Error
     *
     * @return void
     */
    public function testDeleteUsersUseCaseError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('UseCase error occurred');

        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $usersServiceMock->method('deleteUser')
            ->willThrowException(new RuntimeException('UseCase error occurred'));

        // DeleteUser のインスタンスを作成
        $deleteUser = new DeleteUser($usersServiceMock);

        // DeleteUser をDIコンテナに登録
        $this->mockService(DeleteUser::class, function () use ($deleteUser) {
            return $deleteUser;
        });

        // リクエストを実行
        $this->delete('/api/users/2');
    }

    /**
     * Tear down
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        Router::reload();
    }
}
