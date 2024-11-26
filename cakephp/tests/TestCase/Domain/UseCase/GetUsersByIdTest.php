<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\GetUsersById;
use Cake\TestSuite\TestCase;
use RuntimeException;

class GetUsersByIdTest extends TestCase
{
    /**
     * @var array
     */
    protected array $mockedUser = [
        'id' => 1,
        'email' => 'test@example.com',
        'created' => '2024-01-01 00:00:00',
        'modified' => '2024-01-01 00:00:00',
    ];

    /**
     * Test invoke method
     *
     * @return void
     */
    public function testInvoke(): void
    {
        // UsersInterfaceのモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // getUsersByIdメソッドが1回呼ばれることを期待
        $usersServiceMock->expects($this->once())
            ->method('getUsersById')
            ->with('1')
            ->willReturn($this->mockedUser);

        // UseCaseを作成
        $useCase = new GetUsersById($usersServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('1');

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->mockedUser, $result);
    }

    /**
     * Test invoke method with non-existent user
     *
     * @return void
     */
    public function testInvokeWithNonExistentUser(): void
    {
        // UsersInterfaceのモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // getUsersByIdメソッドが1回呼ばれ、nullを返すことを期待
        $usersServiceMock->expects($this->once())
            ->method('getUsersById')
            ->with('999')
            ->willReturn(null);

        // UseCaseを作成
        $useCase = new GetUsersById($usersServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('999');

        // 結果を検証
        $this->assertNull($result);
    }

    /**
     * Test invoke method with service error
     *
     * @return void
     */
    public function testInvokeServiceError(): void
    {
        // UsersInterfaceのモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // getUsersByIdメソッドが例外を投げることを期待
        $usersServiceMock->expects($this->once())
            ->method('getUsersById')
            ->with('1')
            ->willThrowException(new RuntimeException('Service error occurred'));

        // UseCaseを作成
        $useCase = new GetUsersById($usersServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Service error occurred');

        // UseCaseを実行
        $useCase->__invoke('1');
    }
}
