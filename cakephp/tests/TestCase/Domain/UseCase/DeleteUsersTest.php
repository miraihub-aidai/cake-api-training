<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\DeleteUsers;
use Cake\TestSuite\TestCase;
use RuntimeException;

class DeleteUsersTest extends TestCase
{
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

        // deleteUsersメソッドが1回呼ばれることを期待
        $usersServiceMock->expects($this->once())
            ->method('deleteUsers')
            ->with('1')
            ->willReturn(true);

        // UseCaseを作成
        $useCase = new DeleteUsers($usersServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('1');

        // 結果を検証
        $this->assertTrue($result);
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

        // deleteUsersメソッドが例外を投げることを期待
        $usersServiceMock->expects($this->once())
            ->method('deleteUsers')
            ->with('1')
            ->willThrowException(new RuntimeException('Unable to delete user'));

        // UseCaseを作成
        $useCase = new DeleteUsers($usersServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to delete user');

        // UseCaseを実行
        $useCase->__invoke('1');
    }
}
