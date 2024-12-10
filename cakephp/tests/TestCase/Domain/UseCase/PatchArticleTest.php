<?php
declare(strict_types=1);

namespace App\Test\TestCase\Domain\UseCase;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\PatchArticle;
use Cake\TestSuite\TestCase;
use RuntimeException;

class PatchArticleTest extends TestCase
{
    /**
     * @var array
     */
    protected array $mockedArticle =
        [
            'id' => 1,
            'title' => 'Test Article 1',
            'body' => 'Content 1',
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
        // ArticlesService のモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // patchArticle メソッドが1回呼ばれることを期待
        $articlesServiceMock->expects($this->once())
            ->method('patchArticle')
            ->willReturn($this->mockedArticle);

        // UseCaseを作成
        $useCase = new PatchArticle($articlesServiceMock);

        // UseCaseを実行
        $result = $useCase('1', $this->mockedArticle);

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertEquals($this->mockedArticle, $result);
    }

    /**
     * Test invoke method with empty result
     *
     * @return void
     */
    public function testInvokeEmpty(): void
    {
        // ArticlesService のモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // patchArticle メソッドが1回呼ばれ、空の配列を返すことを期待
        $articlesServiceMock->expects($this->once())
            ->method('patchArticle')
            ->willReturn([]);

        // UseCaseを作成
        $useCase = new PatchArticle($articlesServiceMock);

        // UseCaseを実行
        $result = $useCase('2', $this->mockedArticle);

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
        // ArticlesService のモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // patchArticle メソッドが例外を投げることを期待
        $articlesServiceMock->expects($this->once())
            ->method('patchArticle')
            ->willThrowException(new RuntimeException('Service error occurred'));

        // UseCaseを作成
        $useCase = new PatchArticle($articlesServiceMock);

        // 例外が投げられることを期待
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Service error occurred');

        // UseCaseを実行
        $useCase('3', $this->mockedArticle);
    }
}
