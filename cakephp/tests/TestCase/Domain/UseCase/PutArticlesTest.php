<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\PutArticles;
use Cake\TestSuite\TestCase;
use RuntimeException;

class PutArticlesTest extends TestCase
{
    /**
     * @var array
     */
    protected array $updatedArticleData = [
        'title' => 'Updated Article',
        'body' => 'Updated content of the article',
        'created' => '2024-01-01 00:00:00',
        'modified' => '2024-01-01 00:00:00',
    ];

    protected array $savedArticleData = [
        'id' => 1,
        'title' => 'Updated Article',
        'body' => 'Updated content of the article',
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

        // putArticlesメソッドが1回呼ばれることを期待
        $articlesServiceMock->expects($this->once())
            ->method('putArticles')
            ->with('1', $this->updatedArticleData)
            ->willReturn($this->savedArticleData);

        // UseCaseを作成
        $useCase = new PutArticles($articlesServiceMock);

        // UseCaseを実行
        $result = $useCase->__invoke('1', $this->updatedArticleData);

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

        // putArticlesメソッドが例外を投げることを期待
        $articlesServiceMock->expects($this->once())
            ->method('putArticles')
            ->with('1', $this->updatedArticleData)
            ->willThrowException(new RuntimeException('Unable to update article'));

        // UseCaseを作成
        $useCase = new PutArticles($articlesServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to update article');

        // UseCaseを実行
        $useCase->__invoke('1', $this->updatedArticleData);
    }
}
