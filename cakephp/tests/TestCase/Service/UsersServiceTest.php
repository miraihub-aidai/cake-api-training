<?php
declare(strict_types=1);

namespace App\Test\TestCase\Service;

use App\Model\Table\UsersTable;
use App\Service\UsersService;
use Cake\Datasource\EntityInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\ResultSet;
use Cake\TestSuite\TestCase;
use RuntimeException;

class UsersServiceTest extends TestCase
{
    /**
     * @var array
     */
    protected array $mockedUsers = [
        [
            'id' => 1,
            'email' => 'test1@example.com',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ],
        [
            'id' => 2,
            'email' => 'test2@example.com',
            'created' => '2024-01-02 00:00:00',
            'modified' => '2024-01-02 00:00:00',
        ],
    ];

    /**
     * Test getUsers method
     *
     * @return void
     */
    public function testGetUsers(): void
    {
        // ResultSetのモックを作成
        /** @var ResultSet&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toArray'])
            ->getMock();

        $resultSetMock->method('toArray')
            ->willReturn($this->mockedUsers);

        // SelectQueryのモックを作成
        /** @var SelectQuery&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'all'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'email', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('all')
            ->willReturn($resultSetMock);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMock();

        $usersTableMock->method('find')
            ->willReturn($selectQueryMock);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->getUsers();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('users', $result);
        $this->assertEquals($this->mockedUsers, $result['users']);
    }

    /**
     * Test getUsers method with empty result
     *
     * @return void
     */
    public function testGetUsersEmpty(): void
    {
        // ResultSetのモックを作成（空の配列を返す）
        /** @var ResultSet&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toArray'])
            ->getMock();

        $resultSetMock->method('toArray')
            ->willReturn([]);

        // SelectQueryのモックを作成
        /** @var SelectQuery&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'all'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'email', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('all')
            ->willReturn($resultSetMock);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMock();

        $usersTableMock->method('find')
            ->willReturn($selectQueryMock);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->getUsers();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('users', $result);
        $this->assertEmpty($result['users']);
    }

    /**
     * Test getUsersById method
     *
     * @return void
     */
    public function testGetUsersById(): void
    {
        // ResultSetのモックを作成
        /** @var ResultSet<array<string, mixed>>&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);
        $entityMock->method('toArray')
            ->willReturn($this->mockedUsers[0]);

        $resultSetMock->method('first')
            ->willReturn($entityMock);

        // SelectQueryのモックを作成
        /** @var SelectQuery<array<string, mixed>>&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'where'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'email', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('where')
            ->with(['id' => 1])
            ->willReturn($resultSetMock);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMock();

        $usersTableMock->method('find')
            ->willReturn($selectQueryMock);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->getUsersById('1');

        // 結果を検証
        $this->assertEquals($this->mockedUsers[0], $result);
    }

    /**
     * Test postUsers method
     *
     * @return void
     */
    public function testPostUsers(): void
    {
        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['newEntity', 'save'])
            ->getMock();

        $newUserData = [
            'email' => 'new@example.com',
            'password' => 'password123',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ];

        $savedUserData = [
            'id' => 1,
            'email' => 'new@example.com',
            'password' => 'hashed_password',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ];

        $entityMock = $this->createMock(EntityInterface::class);
        $entityMock->method('toArray')
            ->willReturn($savedUserData);

        $usersTableMock->method('newEntity')
            ->with($newUserData)
            ->willReturn($entityMock);

        $usersTableMock->method('save')
            ->with($entityMock)
            ->willReturn($entityMock);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->postUsers($newUserData);

        // 結果を検証
        $expectedData = $savedUserData;
        unset($expectedData['password']);
        $this->assertEquals($expectedData, $result);
        $this->assertArrayNotHasKey('password', $result);
    }

    /**
     * Test postUsers method with save failure
     *
     * @return void
     */
    public function testPostUsersSaveFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to save user');

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['newEntity', 'save'])
            ->getMock();

        $newUserData = [
            'email' => 'new@example.com',
            'password' => 'password123',
        ];

        $entityMock = $this->createMock(EntityInterface::class);

        $usersTableMock->method('newEntity')
            ->with($newUserData)
            ->willReturn($entityMock);

        $usersTableMock->method('save')
            ->willReturn(false);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行（例外が発生することを期待）
        $service->postUsers($newUserData);
    }

    /**
     * Test putUsers method
     *
     * @return void
     */
    public function testPutUsers(): void
    {
        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'patchEntity', 'save'])
            ->getMock();

        $updateData = [
            'email' => 'updated@example.com',
        ];

        $savedUserData = [
            'id' => 1,
            'email' => 'updated@example.com',
            'password' => 'hashed_password',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ];

        $entityMock = $this->createMock(EntityInterface::class);
        $entityMock->method('toArray')
            ->willReturn($savedUserData);

        $usersTableMock->method('get')
            ->willReturn($entityMock);

        $usersTableMock->method('patchEntity')
            ->willReturn($entityMock);

        $usersTableMock->method('save')
            ->willReturn($entityMock);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->putUsers('1', $updateData);

        // 結果を検証
        $expectedData = $savedUserData;
        unset($expectedData['password']);
        $this->assertEquals($expectedData, $result);
        $this->assertArrayNotHasKey('password', $result);
    }

    /**
     * Test putUsers method with save failure
     *
     * @return void
     */
    public function testPutUsersSaveFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to update user');

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'patchEntity', 'save'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);

        $usersTableMock->method('get')
            ->willReturn($entityMock);

        $usersTableMock->method('patchEntity')
            ->willReturn($entityMock);

        $usersTableMock->method('save')
            ->willReturn(false);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行（例外が発生することを期待）
        $service->putUsers('1', ['email' => 'updated@example.com']);
    }

    /**
     * Test deleteUsers method
     *
     * @return void
     */
    public function testDeleteUsers(): void
    {
        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);

        $usersTableMock->method('get')
            ->willReturn($entityMock);

        $usersTableMock->method('delete')
            ->willReturn(true);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->deleteUsers('1');

        // 結果を検証
        $this->assertTrue($result);
    }

    /**
     * Test deleteUsers method with failure
     *
     * @return void
     */
    public function testDeleteUsersFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to delete user');

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);

        $usersTableMock->method('get')
            ->willReturn($entityMock);

        $usersTableMock->method('delete')
            ->willReturn(false);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行（例外が発生することを期待）
        $service->deleteUsers('1');
    }
}
