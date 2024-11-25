<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\PutUsers;
use Cake\TestSuite\TestCase;
use RuntimeException;

class PutUsersTest extends TestCase
{
    /**
     * @var array
     */
    protected array $updatedUserData = [
        'email' => 'updated@example.com',
        'password' => 'newpassword123',
        'created' => '2024-01-01 00:00:00',
        'modified' => '2024-01-01 00:00:00',
    ];

    protected array $savedUserData = [
        'id' => 1,
        'email' => 'updated@example.com',
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

        // putUsersメソッドが1回呼ばれることを期待
        $usersServiceMock->expects($this->once())
            ->method('putUsers')
            ->with('1', $this->updatedUserData)
            ->willReturn($this->savedUserData);

        // UseCaseを作成
        $useCase = new PutUsers($usersServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('1', $this->updatedUserData);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->savedUserData, $result);
        $this->assertArrayNotHasKey('password', $result);
    }

    /**
     * Test invoke method with only email update
     *
     * @return void
     */
    public function testInvokeEmailOnly(): void
    {
        // UsersInterfaceのモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        $emailOnlyData = ['email' => 'updated@example.com'];

        // putUsersメソッドが1回呼ばれることを期待
        $usersServiceMock->expects($this->once())
            ->method('putUsers')
            ->with('1', $emailOnlyData)
            ->willReturn($this->savedUserData);

        // UseCaseを作成
        $useCase = new PutUsers($usersServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('1', $emailOnlyData);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->savedUserData, $result);
        $this->assertArrayNotHasKey('password', $result);
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

        // putUsersメソッドが例外を投げることを期待
        $usersServiceMock->expects($this->once())
            ->method('putUsers')
            ->with('1', $this->updatedUserData)
            ->willThrowException(new RuntimeException('Unable to update user'));

        // UseCaseを作成
        $useCase = new PutUsers($usersServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to update user');

        // UseCaseを実行
        $useCase->__invoke('1', $this->updatedUserData);
    }
}
