<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\GetArticlesById;
use Cake\TestSuite\TestCase;
use RuntimeException;

class GetArticlesByIdTest extends TestCase
{
    /**
     * @var array
     */
    protected array $mockedArticle = [
        'id' => 1,
        'title' => 'Test Article',
        'body' => 'Content of the test article',
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
        // ArticlesInterfaceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // getArticlesByIdメソッドが1回呼ばれることを期待
        $articlesServiceMock->expects($this->once())
            ->method('getArticlesById')
            ->with('1')
            ->willReturn($this->mockedArticle);

        // UseCaseを作成
        $useCase = new GetArticlesById($articlesServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('1');

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->mockedArticle, $result);
    }

    /**
     * Test invoke method with non-existent article
     *
     * @return void
     */
    public function testInvokeWithNonExistentArticle(): void
    {
        // ArticlesInterfaceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // getArticlesByIdメソッドが1回呼ばれ、nullを返すことを期待
        $articlesServiceMock->expects($this->once())
            ->method('getArticlesById')
            ->with('999')
            ->willReturn(null);

        // UseCaseを作成
        $useCase = new GetArticlesById($articlesServiceMock);

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
        // ArticlesInterfaceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // getArticlesByIdメソッドが例外を投げることを期待
        $articlesServiceMock->expects($this->once())
            ->method('getArticlesById')
            ->with('1')
            ->willThrowException(new RuntimeException('Service error occurred'));

        // UseCaseを作成
        $useCase = new GetArticlesById($articlesServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Service error occurred');

        // UseCaseを実行
        $useCase->__invoke('1');
    }
}
