<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\GetArticles;
use Cake\TestSuite\TestCase;
use RuntimeException;

class GetArticlesTest extends TestCase
{
    /**
     * @var array
     */
    protected array $mockedArticles = [
        [
            'id' => 1,
            'title' => 'Test Article 1',
            'body' => 'Content 1',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ],
        [
            'id' => 2,
            'title' => 'Test Article 2',
            'body' => 'Content 2',
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
        // ArticlesServiceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // getArticlesメソッドが1回呼ばれることを期待
        $articlesServiceMock->expects($this->once())
            ->method('getArticles')
            ->willReturn(['articles' => $this->mockedArticles]);

        // UseCaseを作成
        $useCase = new GetArticles($articlesServiceMock);

        // UseCaseを実行
        $result = $useCase();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('articles', $result);
        $this->assertEquals($this->mockedArticles, $result['articles']);
    }

    /**
     * Test invoke method with empty result
     *
     * @return void
     */
    public function testInvokeEmpty(): void
    {
        // ArticlesServiceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // getArticlesメソッドが1回呼ばれ、空の配列を返すことを期待
        $articlesServiceMock->expects($this->once())
            ->method('getArticles')
            ->willReturn(['articles' => []]);

        // UseCaseを作成
        $useCase = new GetArticles($articlesServiceMock);

        // UseCaseを実行
        $result = $useCase();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('articles', $result);
        $this->assertEmpty($result['articles']);
    }

    /**
     * Test invoke method with service error
     *
     * @return void
     */
    public function testInvokeServiceError(): void
    {
        // ArticlesServiceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // getArticlesメソッドが例外を投げることを期待
        $articlesServiceMock->expects($this->once())
            ->method('getArticles')
            ->willThrowException(new RuntimeException('Service error occurred'));

        // UseCaseを作成
        $useCase = new GetArticles($articlesServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Service error occurred');

        // UseCaseを実行
        $useCase();
    }
}
