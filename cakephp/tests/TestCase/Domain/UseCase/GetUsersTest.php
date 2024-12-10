<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\GetUsers;
use Cake\TestSuite\TestCase;
use RuntimeException;

class GetUsersTest extends TestCase
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
     * Test invoke method
     *
     * @return void
     */
    public function testInvoke(): void
    {
        // UsersServiceのモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // getUsersメソッドが1回呼ばれることを期待
        $usersServiceMock->expects($this->once())
            ->method('getUsers')
            ->willReturn(['users' => $this->mockedUsers]);

        // UseCaseを作成
        $useCase = new GetUsers($usersServiceMock);

        // UseCaseを実行
        $result = $useCase();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('users', $result);
        $this->assertEquals($this->mockedUsers, $result['users']);
    }

    /**
     * Test invoke method with empty result
     *
     * @return void
     */
    public function testInvokeEmpty(): void
    {
        // UsersServiceのモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // getUsersメソッドが1回呼ばれ、空の配列を返すことを期待
        $usersServiceMock->expects($this->once())
            ->method('getUsers')
            ->willReturn(['users' => []]);

        // UseCaseを作成
        $useCase = new GetUsers($usersServiceMock);

        // UseCaseを実行
        $result = $useCase();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('users', $result);
        $this->assertEmpty($result['users']);
    }

    /**
     * Test invoke method with service error
     *
     * @return void
     */
    public function testInvokeServiceError(): void
    {
        // UsersServiceのモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // getUsersメソッドが例外を投げることを期待
        $usersServiceMock->expects($this->once())
            ->method('getUsers')
            ->willThrowException(new RuntimeException('Service error occurred'));

        // UseCaseを作成
        $useCase = new GetUsers($usersServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Service error occurred');

        // UseCaseを実行
        $useCase();
    }
}
