<?php
declare(strict_types=1);

namespace App\Test\TestCase\ServiceProvider;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\CreateUser;
use App\Domain\UseCase\DeleteUser;
use App\Domain\UseCase\GetUserById;
use App\Domain\UseCase\GetUsers;
use App\Domain\UseCase\PatchUser;
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
            GetUserById::class,
            CreateUser::class,
            PatchUser::class,
            DeleteUser::class,
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

        // GetUserById が正しく解決されることを確認
        $getUserById = $this->container->get(GetUserById::class);
        $this->assertInstanceOf(GetUserById::class, $getUserById);

        // CreateUser が正しく解決されることを確認
        $createUser = $this->container->get(CreateUser::class);
        $this->assertInstanceOf(CreateUser::class, $createUser);

        // PatchUser が正しく解決されることを確認
        $patchUser = $this->container->get(PatchUser::class);
        $this->assertInstanceOf(PatchUser::class, $patchUser);

        // DeleteUser が正しく解決されることを確認
        $deleteUser = $this->container->get(DeleteUser::class);
        $this->assertInstanceOf(DeleteUser::class, $deleteUser);
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

        $getUserById1 = $this->container->get(GetUserById::class);
        $getUserById2 = $this->container->get(GetUserById::class);
        $this->assertSame($getUserById1, $getUserById2);

        $createUser1 = $this->container->get(CreateUser::class);
        $createUser2 = $this->container->get(CreateUser::class);
        $this->assertSame($createUser1, $createUser2);

        $patchUser1 = $this->container->get(PatchUser::class);
        $patchUser2 = $this->container->get(PatchUser::class);
        $this->assertSame($patchUser1, $patchUser2);

        $deleteUser1 = $this->container->get(DeleteUser::class);
        $deleteUser2 = $this->container->get(DeleteUser::class);
        $this->assertSame($deleteUser1, $deleteUser2);
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

        // GetUserById の依存関係チェーンを確認
        $getUserById = $this->container->get(GetUserById::class);

        // CreateUser の依存関係チェーンを確認
        $createUser = $this->container->get(CreateUser::class);

        // PatchUser の依存関係チェーンを確認
        $patchUser = $this->container->get(PatchUser::class);

        // DeleteUser の依存関係チェーンを確認
        $deleteUser = $this->container->get(DeleteUser::class);

        // リフレクションを使用して protected プロパティにアクセス
        $userServiceReflection = new ReflectionProperty(GetUsers::class, 'usersService');
        $userServiceReflection->setAccessible(true);
        $usersService = $userServiceReflection->getValue($getUsers);

        $userServiceReflection = new ReflectionProperty(GetUserById::class, 'usersService');
        $userServiceReflection->setAccessible(true);
        $usersService = $userServiceReflection->getValue($getUserById);

        $userServiceReflection = new ReflectionProperty(CreateUser::class, 'usersService');
        $userServiceReflection->setAccessible(true);
        $usersService = $userServiceReflection->getValue($createUser);

        $userServiceReflection = new ReflectionProperty(PatchUser::class, 'usersService');
        $userServiceReflection->setAccessible(true);
        $usersService = $userServiceReflection->getValue($patchUser);

        $userServiceReflection = new ReflectionProperty(DeleteUser::class, 'usersService');
        $userServiceReflection->setAccessible(true);
        $usersService = $userServiceReflection->getValue($deleteUser);

        // UsersService の依存関係を確認
        $usersTableReflection = new ReflectionProperty(UsersService::class, 'users');
        $usersTableReflection->setAccessible(true);
        $usersTable = $usersTableReflection->getValue($usersService);

        // 型のチェック
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
