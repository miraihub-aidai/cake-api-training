<?php
declare(strict_types=1);

namespace App\Test\TestCase\ServiceProvider;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\DeleteUsers;
use App\Domain\UseCase\GetUsers;
use App\Domain\UseCase\GetUsersById;
use App\Domain\UseCase\PostUsers;
use App\Domain\UseCase\PutUsers;
use App\Model\Table\UsersTable;
use App\Service\UsersService;
use App\ServiceProvider\UsersServiceProvider;
use Cake\Core\Container;
use Cake\TestSuite\TestCase;
use ReflectionProperty;

class UsersServiceProviderTest extends TestCase
{
    /**
     * @var \Cake\Core\Container
     */
    protected Container $container;

    /**
     * @var \App\ServiceProvider\UsersServiceProvider
     */
    protected UsersServiceProvider $provider;

    /**
     * Setup method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();
        $this->provider = new UsersServiceProvider();
    }

    /**
     * Test provider provides the correct services
     *
     * @return void
     */
    public function testProvides(): void
    {
        $expectedServices = [
            GetUsers::class,
            GetUsersById::class,
            PostUsers::class,
            PutUsers::class,
            DeleteUsers::class,
            UsersInterface::class,
            UsersTable::class,
        ];

        // 各サービスがprovidesで提供されることを確認
        foreach ($expectedServices as $service) {
            $this->assertTrue(
                $this->provider->provides($service),
                "Provider should provide {$service}"
            );
        }

        // 存在しないサービスは提供されないことを確認
        $this->assertFalse(
            $this->provider->provides('NonExistentService'),
            'Provider should not provide non-existent service'
        );
    }

    /**
     * Test services registration
     *
     * @return void
     */
    public function testServices(): void
    {
        // サービスを登録
        $this->provider->services($this->container);

        // UsersTable が正しく解決されることを確認
        $usersTable = $this->container->get(UsersTable::class);
        $this->assertInstanceOf(UsersTable::class, $usersTable);

        // UsersService が正しく解決されることを確認
        $usersService = $this->container->get(UsersInterface::class);
        $this->assertInstanceOf(UsersService::class, $usersService);
        $this->assertInstanceOf(UsersInterface::class, $usersService);

        // GetUsers が正しく解決されることを確認
        $getUsers = $this->container->get(GetUsers::class);
        $this->assertInstanceOf(GetUsers::class, $getUsers);

        // GetUsersById が正しく解決されることを確認
        $getUsersById = $this->container->get(GetUsersById::class);
        $this->assertInstanceOf(GetUsersById::class, $getUsersById);

        // PostUsers が正しく解決されることを確認
        $postUsers = $this->container->get(PostUsers::class);
        $this->assertInstanceOf(PostUsers::class, $postUsers);

        // PutUsers が正しく解決されることを確認
        $putUsers = $this->container->get(PutUsers::class);
        $this->assertInstanceOf(PutUsers::class, $putUsers);

        // DeleteUsers が正しく解決されることを確認
        $deleteUsers = $this->container->get(DeleteUsers::class);
        $this->assertInstanceOf(DeleteUsers::class, $deleteUsers);
    }

    /**
     * Test services are shared (singleton)
     *
     * @return void
     */
    public function testServicesAreShared(): void
    {
        // サービスを登録
        $this->provider->services($this->container);

        // 同じインスタンスが返されることを確認
        $usersTable1 = $this->container->get(UsersTable::class);
        $usersTable2 = $this->container->get(UsersTable::class);
        $this->assertSame($usersTable1, $usersTable2);

        $usersService1 = $this->container->get(UsersInterface::class);
        $usersService2 = $this->container->get(UsersInterface::class);
        $this->assertSame($usersService1, $usersService2);

        $getUsers1 = $this->container->get(GetUsers::class);
        $getUsers2 = $this->container->get(GetUsers::class);
        $this->assertSame($getUsers1, $getUsers2);

        // GetUsersByIdのシングルトン確認
        $getUsersById1 = $this->container->get(GetUsersById::class);
        $getUsersById2 = $this->container->get(GetUsersById::class);
        $this->assertSame($getUsersById1, $getUsersById2);

        // PostUsersのシングルトン確認
        $postUsers1 = $this->container->get(PostUsers::class);
        $postUsers2 = $this->container->get(PostUsers::class);
        $this->assertSame($postUsers1, $postUsers2);

        // PutUsersのシングルトン確認
        $putUsers1 = $this->container->get(PutUsers::class);
        $putUsers2 = $this->container->get(PutUsers::class);
        $this->assertSame($putUsers1, $putUsers2);

        // DeleteUsersのシングルトン確認
        $deleteUsers1 = $this->container->get(DeleteUsers::class);
        $deleteUsers2 = $this->container->get(DeleteUsers::class);
        $this->assertSame($deleteUsers1, $deleteUsers2);
    }

    /**
     * Test dependency injection chain
     *
     * @return void
     */
    public function testDependencyInjectionChain(): void
    {
        // サービスを登録
        $this->provider->services($this->container);

        // GetUsers の依存関係チェーンを確認
        $getUsers = $this->container->get(GetUsers::class);

        // リフレクションを使用して protected プロパティにアクセス
        $userServiceReflection = new ReflectionProperty(GetUsers::class, 'usersService');
        $userServiceReflection->setAccessible(true);
        $usersService = $userServiceReflection->getValue($getUsers);

        // UsersService の依存関係を確認
        $usersTableReflection = new ReflectionProperty(UsersService::class, 'users');
        $usersTableReflection->setAccessible(true);
        $usersTable = $usersTableReflection->getValue($usersService);

        // 型のチェック
        $this->assertInstanceOf(UsersService::class, $usersService);
        $this->assertInstanceOf(UsersTable::class, $usersTable);

        // GetUsersById の依存関係チェーンを確認
        $getUsersById = $this->container->get(GetUsersById::class);

        $userServiceReflection = new ReflectionProperty(GetUsersById::class, 'usersService');
        $userServiceReflection->setAccessible(true);
        $usersService = $userServiceReflection->getValue($getUsersById);

        $usersTableReflection = new ReflectionProperty(UsersService::class, 'users');
        $usersTableReflection->setAccessible(true);
        $usersTable = $usersTableReflection->getValue($usersService);

        $this->assertInstanceOf(UsersService::class, $usersService);
        $this->assertInstanceOf(UsersTable::class, $usersTable);

        // PostUsers の依存関係チェーンを確認
        $postUsers = $this->container->get(PostUsers::class);

        $userServiceReflection = new ReflectionProperty(PostUsers::class, 'usersService');
        $userServiceReflection->setAccessible(true);
        $usersService = $userServiceReflection->getValue($postUsers);

        $usersTableReflection = new ReflectionProperty(UsersService::class, 'users');
        $usersTableReflection->setAccessible(true);
        $usersTable = $usersTableReflection->getValue($usersService);

        $this->assertInstanceOf(UsersService::class, $usersService);
        $this->assertInstanceOf(UsersTable::class, $usersTable);

        // PutUsers の依存関係チェーンを確認
        $putUsers = $this->container->get(PutUsers::class);

        $userServiceReflection = new ReflectionProperty(PutUsers::class, 'usersService');
        $userServiceReflection->setAccessible(true);
        $usersService = $userServiceReflection->getValue($putUsers);

        $usersTableReflection = new ReflectionProperty(UsersService::class, 'users');
        $usersTableReflection->setAccessible(true);
        $usersTable = $usersTableReflection->getValue($usersService);

        $this->assertInstanceOf(UsersService::class, $usersService);
        $this->assertInstanceOf(UsersTable::class, $usersTable);

        // DeleteUsers の依存関係チェーンを確認
        $deleteUsers = $this->container->get(DeleteUsers::class);

        $userServiceReflection = new ReflectionProperty(DeleteUsers::class, 'usersService');
        $userServiceReflection->setAccessible(true);
        $usersService = $userServiceReflection->getValue($deleteUsers);

        $usersTableReflection = new ReflectionProperty(UsersService::class, 'users');
        $usersTableReflection->setAccessible(true);
        $usersTable = $usersTableReflection->getValue($usersService);

        $this->assertInstanceOf(UsersService::class, $usersService);
        $this->assertInstanceOf(UsersTable::class, $usersTable);
    }

    /**
     * Tear down method
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->container, $this->provider);
    }
}
