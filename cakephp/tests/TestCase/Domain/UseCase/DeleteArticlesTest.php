<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\DeleteArticles;
use Cake\TestSuite\TestCase;
use RuntimeException;

class DeleteArticlesTest extends TestCase
{
    /**
     * Test invoke method
     *
     * @return void
     */
    public function testInvoke(): void
    {
        // ArticlesInterfaceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // deleteArticlesメソッドが1回呼ばれることを期待
        $articlesServiceMock->expects($this->once())
            ->method('deleteArticles')
            ->with('1')
            ->willReturn(true);

        // UseCaseを作成
        $useCase = new DeleteArticles($articlesServiceMock);

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
        // ArticlesInterfaceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // deleteArticlesメソッドが例外を投げることを期待
        $articlesServiceMock->expects($this->once())
            ->method('deleteArticles')
            ->with('1')
            ->willThrowException(new RuntimeException('Unable to delete article'));

        // UseCaseを作成
        $useCase = new DeleteArticles($articlesServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to delete article');

        // UseCaseを実行
        $useCase->__invoke('1');
    }
}
