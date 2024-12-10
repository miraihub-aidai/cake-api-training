<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\CreateUser;
use Cake\TestSuite\TestCase;
use RuntimeException;

class CreateUserTest extends TestCase
{
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
     * Test invoke method
     *
     * @return void
     */
    public function testInvoke(): void
    {
        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // createUser メソッドが1回呼ばれることを期待
        $usersServiceMock->expects($this->once())
            ->method('createUser')
            ->willReturn($this->mockedUser);

        // UseCaseを作成
        $useCase = new CreateUser($usersServiceMock);

        // UseCaseを実行
        $result = $useCase($this->mockedUser);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->mockedUser, $result);
    }

    /**
     * Test invoke method with empty result
     *
     * @return void
     */
    public function testInvokeEmpty(): void
    {
        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // createUser メソッドが1回呼ばれ、空の配列を返すことを期待
        $usersServiceMock->expects($this->once())
            ->method('createUser')
            ->willReturn([]);

        // UseCaseを作成
        $useCase = new CreateUser($usersServiceMock);

        // UseCaseを実行
        $result = $useCase($this->mockedUser);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    /**
     * Test invoke method with service error
     *
     * @return void
     */
    public function testInvokeServiceError(): void
    {
        // UsersService のモックを作成
        /** @var UsersInterface&\PHPUnit\Framework\MockObject\MockObject $usersServiceMock */
        $usersServiceMock = $this->getMockBuilder(UsersInterface::class)
            ->getMock();

        // createUser メソッドが例外を投げることを期待
        $usersServiceMock->expects($this->once())
            ->method('createUser')
            ->willThrowException(new RuntimeException('Service error occurred'));

        // UseCaseを作成
        $useCase = new CreateUser($usersServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Service error occurred');

        // UseCaseを実行
        $useCase($this->mockedUser);
    }
}
