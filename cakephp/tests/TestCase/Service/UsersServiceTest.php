<?php
declare(strict_types=1);

namespace App\Test\TestCase\Service;

use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use App\Service\UsersService;
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
    protected array $mockedUser =
        [
            'id' => 1,
            'email' => 'TesMail1@sample.co.jp',
            'password' => 'user1',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ];

    /**
     * @var array
     */
    protected array $mockedEmptyUser =
        [
            'id' => null,
            'email' => null,
            'password' => null,
            'created' => null,
            'modified' => null,
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
            ->with(['id', 'email', 'password', 'created', 'modified'])
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
            ->with(['id', 'email', 'password', 'created', 'modified'])
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
     * Test getUsers method with database error
     *
     * @return void
     */
    public function testGetUsersDatabaseError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Database error occurred');

        // SelectQueryのモックを作成（例外を投げる）
        /** @var SelectQuery&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'all'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'email', 'password', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('all')
            ->willThrowException(new RuntimeException('Database error occurred'));

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

        // メソッドを実行（例外が発生することを期待）
        $service->getUsers();
    }

    /**
     * Test getUserById method
     *
     * @return void
     */
    public function testGetUserById(): void
    {
        // ResultSetのモックを作成
        /** @var ResultSet&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toArray'])
            ->getMock();

        $resultSetMock->method('toArray')
            ->willReturn($this->mockedUser);

        // SelectQueryのモックを作成
        /** @var SelectQuery&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'firstOrFail'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'email', 'password', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('firstOrFail')
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
        $result = $service->getUserById('1');

        // 結果を検証
        $this->assertIsArray($result);
        //$this->assertArrayHasKey('user', $result);
        $this->assertEquals($this->mockedUser, $result);
    }

    /**
     * Test getUserById method with empty result
     *
     * @return void
     */
    public function testGetUserByIdEmpty(): void
    {
        // ResultSetのモックを作成
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
            ->onlyMethods(['select', 'firstOrFail'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'email', 'password', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('firstOrFail')
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
        $result = $service->getUserById('2');

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Test getUserById method with database error
     *
     * @return void
     */
    public function testGetUserByIdDatabaseError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Database error occurred');

        // SelectQueryのモックを作成（例外を投げる）
        /** @var SelectQuery&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'firstOrFail'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'email', 'password', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('firstOrFail')
            ->willThrowException(new RuntimeException('Database error occurred'));

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

        // メソッドを実行（例外が発生することを期待）
        $service->getUserById('3');
    }

    /**
     * Test createUser method
     *
     * @return void
     */
    public function testCreateUser(): void
    {
        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['newEmptyEntity', 'patchEntity', 'save'])
            ->getMock();

        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();

        $usersTableMock->method('newEmptyEntity')
            ->willReturn($usersEntityMock);

        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock2 */
        $usersEntityMock2 = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock2->method('toArray')
            ->willReturn($this->mockedUser);

        $usersTableMock->method('patchEntity')
            ->willReturn($usersEntityMock2);

        $usersTableMock->method('save')
            ->willReturn($usersEntityMock2);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->createUser($this->mockedUser);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->mockedUser, $result);
    }

    /**
     * Test createUser method with save failed
     *
     * @return void
     */
    public function testCreateUserSaveFailed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('User Create Error');

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['newEmptyEntity', 'patchEntity', 'save'])
            ->getMock();

        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();

        $usersTableMock->method('newEmptyEntity')
            ->willReturn($usersEntityMock);

        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock2 */
        $usersEntityMock2 = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock2->method('toArray')
            ->willReturn($this->mockedUser);

        $usersTableMock->method('patchEntity')
            ->willReturn($usersEntityMock2);

        $usersTableMock->method('save')
            ->willReturn(false);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $service->createUser($this->mockedUser);
    }

    /**
     * Test createUser method with database error
     *
     * @return void
     */
    public function testCreateUserDatabaseError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Database error occurred');

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['newEmptyEntity', 'patchEntity', 'save'])
            ->getMock();

        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();

        $usersTableMock->method('newEmptyEntity')
            ->willReturn($usersEntityMock);

        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock2 */
        $usersEntityMock2 = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock2->method('toArray')
            ->willReturn($this->mockedUser);

        $usersTableMock->method('patchEntity')
            ->willReturn($usersEntityMock2);

        $usersTableMock->method('save')
            ->willThrowException(new RuntimeException('Database error occurred'));

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $service->createUser($this->mockedUser);
    }

    /**
     * Test patchUser method
     *
     * @return void
     */
    public function testPatchUser(): void
    {
        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock->method('toArray')
            ->willReturn($this->mockedUser);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'patchEntity', 'save'])
            ->getMock();

        $usersTableMock->method('get')
            ->willReturn($usersEntityMock);

        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock2 */
        $usersEntityMock2 = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock2->method('toArray')
            ->willReturn($this->mockedUser);

        $usersTableMock->method('patchEntity')
            ->willReturn($usersEntityMock2);

        $usersTableMock->method('save')
            ->willReturn($usersEntityMock2);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->patchUser('1', $this->mockedUser);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->mockedUser, $result);
    }

    /**
     * Test patchUser method with data not found
     *
     * @return void
     */
    public function testPatchUserDataNotFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('User Not Found Error');

        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock->method('toArray')
            ->willReturn([]);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'patchEntity', 'save'])
            ->getMock();

        $usersTableMock->method('get')
            ->willReturn($usersEntityMock);

        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock2 */
        $usersEntityMock2 = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock2->method('toArray')
            ->willReturn($this->mockedUser);

        $usersTableMock->method('patchEntity')
            ->willReturn($usersEntityMock2);

        $usersTableMock->method('save')
            ->willReturn($usersEntityMock2);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $service->patchUser('2', $this->mockedUser);
    }

    /**
     * Test patchUser method with save failed
     *
     * @return void
     */
    public function testPatchUserSaveFailed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('User Patch Error');

        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock->method('toArray')
            ->willReturn($this->mockedUser);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'patchEntity', 'save'])
            ->getMock();

        $usersTableMock->method('get')
            ->willReturn($usersEntityMock);

        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock2 */
        $usersEntityMock2 = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock2->method('toArray')
            ->willReturn($this->mockedUser);

        $usersTableMock->method('patchEntity')
            ->willReturn($usersEntityMock2);

        $usersTableMock->method('save')
            ->willReturn(false);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $service->patchUser('3', $this->mockedUser);
    }

    /**
     * Test patchUser method with database error by find
     *
     * @return void
     */
    public function testPatchUserDatabaseErrorByFind(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Database error occurred');

        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock->method('toArray')
            ->willReturn($this->mockedUser);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'patchEntity', 'save'])
            ->getMock();

        $usersTableMock->method('get')
            ->willThrowException(new RuntimeException('Database error occurred'));

        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock2 */
        $usersEntityMock2 = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock2->method('toArray')
            ->willReturn($this->mockedUser);

        $usersTableMock->method('patchEntity')
            ->willReturn($usersEntityMock2);

        $usersTableMock->method('save')
            ->willReturn($usersEntityMock2);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->patchUser('4', $this->mockedUser);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->mockedUser, $result);
    }

    /**
     * Test patchUser method with database error by Save
     *
     * @return void
     */
    public function testPatchUserDatabaseErrorBySave(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Database error occurred');

        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock->method('toArray')
            ->willReturn($this->mockedUser);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'patchEntity', 'save'])
            ->getMock();

        $usersTableMock->method('get')
            ->willReturn($usersEntityMock);

        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock2 */
        $usersEntityMock2 = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock2->method('toArray')
            ->willReturn($this->mockedUser);

        $usersTableMock->method('patchEntity')
            ->willReturn($usersEntityMock2);

        $usersTableMock->method('save')
            ->willThrowException(new RuntimeException('Database error occurred'));

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->patchUser('5', $this->mockedUser);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->mockedUser, $result);
    }

    /**
     * Test deleteUser method
     *
     * @return void
     */
    public function testDeleteUser(): void
    {
        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock->method('toArray')
            ->willReturn($this->mockedUser);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $usersTableMock->method('get')
            ->willReturn($usersEntityMock);

        $usersTableMock->method('delete')
            ->willReturn(true);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $result = $service->deleteUser('1');

        // 結果を検証
        $this->assertEquals(true, $result);
    }

    /**
     * Test deleteUser method with data not found
     *
     * @return void
     */
    public function testDeleteUserDataNotFound(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('User Not Found Error');

        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock->method('toArray')
            ->willReturn([]);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $usersTableMock->method('get')
            ->willReturn($usersEntityMock);

        $usersTableMock->method('delete')
            ->willReturn(true);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $service->deleteUser('2');
    }

    /**
     * Test deleteUser method with delete failed
     *
     * @return void
     */
    public function testDeleteUserDeleteFailed(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('User Delete Error');

        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock->method('toArray')
            ->willReturn($this->mockedUser);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $usersTableMock->method('get')
            ->willReturn($usersEntityMock);

        $usersTableMock->method('delete')
            ->willReturn(false);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行
        $service->deleteUser('3');
    }

    /**
     * Test deleteUser method with database error by find
     *
     * @return void
     */
    public function testDeleteUserDatabaseErrorByFind(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Database error occurred');

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $usersTableMock->method('get')
            ->willThrowException(new RuntimeException('Database error occurred'));

        $usersTableMock->method('delete')
            ->willReturn(true);

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行（例外が発生することを期待）
        $service->deleteUser('4');
    }

    /**
     * Test deleteUser method with database error by delete
     *
     * @return void
     */
    public function testDeleteUserDatabaseErrorByDelete(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Database error occurred');

        // User エンティティのモックを作成
        /** @var User&\PHPUnit\Framework\MockObject\MockObject $usersEntityMock */
        $usersEntityMock = $this->getMockBuilder(User::class)
            ->getMock();
        $usersEntityMock->method('toArray')
            ->willReturn($this->mockedUser);

        // UsersTableのモックを作成
        /** @var UsersTable&\PHPUnit\Framework\MockObject\MockObject $usersTableMock */
        $usersTableMock = $this->getMockBuilder(UsersTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $usersTableMock->method('get')
            ->willReturn($usersEntityMock);

        $usersTableMock->method('delete')
            ->willThrowException(new RuntimeException('Database error occurred'));

        // テスト対象のServiceを作成
        $service = new UsersService($usersTableMock);

        // メソッドを実行（例外が発生することを期待）
        $service->deleteUser('5');
    }
}
