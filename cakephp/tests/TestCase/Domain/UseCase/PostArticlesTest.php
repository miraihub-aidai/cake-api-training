<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\PostArticles;
use Cake\TestSuite\TestCase;
use RuntimeException;

class PostArticlesTest extends TestCase
{
    /**
     * @var array
     */
    protected array $newArticleData = [
        'title' => 'New Article',
        'body' => 'Content of the new article',
        'created' => '2024-01-01 00:00:00',
        'modified' => '2024-01-01 00:00:00',
    ];

    protected array $savedArticleData = [
        'id' => 1,
        'title' => 'New Article',
        'body' => 'Content of the new article',
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

        // postArticlesメソッドが1回呼ばれることを期待
        $articlesServiceMock->expects($this->once())
            ->method('postArticles')
            ->with($this->newArticleData)
            ->willReturn($this->savedArticleData);

        // UseCaseを作成
        $useCase = new PostArticles($articlesServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke($this->newArticleData);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->savedArticleData, $result);
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

        // postArticlesメソッドが例外を投げることを期待
        $articlesServiceMock->expects($this->once())
            ->method('postArticles')
            ->with($this->newArticleData)
            ->willThrowException(new RuntimeException('Unable to save article'));

        // UseCaseを作成
        $useCase = new PostArticles($articlesServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to save article');

        // UseCaseを実行
        $useCase->__invoke($this->newArticleData);
    }
}
