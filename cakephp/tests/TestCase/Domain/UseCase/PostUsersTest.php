<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\PostUsers;
use Cake\TestSuite\TestCase;
use RuntimeException;

class PostUsersTest extends TestCase
{
    /**
     * @var array
     */
    protected array $newUserData = [
        'email' => 'test@example.com',
        'password' => 'password123',
        'created' => '2024-01-01 00:00:00',
        'modified' => '2024-01-01 00:00:00',
    ];

    protected array $savedUserData = [
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

        // postUsersメソッドが1回呼ばれることを期待
        $usersServiceMock->expects($this->once())
            ->method('postUsers')
            ->with($this->newUserData)
            ->willReturn($this->savedUserData);

        // UseCaseを作成
        $useCase = new PostUsers($usersServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke($this->newUserData);

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

        // postUsersメソッドが例外を投げることを期待
        $usersServiceMock->expects($this->once())
            ->method('postUsers')
            ->with($this->newUserData)
            ->willThrowException(new RuntimeException('Unable to save user'));

        // UseCaseを作成
        $useCase = new PostUsers($usersServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to save user');

        // UseCaseを実行
        $useCase->__invoke($this->newUserData);
    }
}
