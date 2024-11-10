<?php
declare(strict_types=1);

namespace App\Test\TestCase\Service;

use App\Model\Table\ArticlesTable;
use App\Service\ArticlesService;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\ResultSet;
use Cake\TestSuite\TestCase;
use RuntimeException;

class ArticlesServiceTest extends TestCase
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
     * Test getArticles method
     *
     * @return void
     */
    public function testGetArticles(): void
    {
        // ResultSetのモックを作成
        /** @var ResultSet&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toArray'])
            ->getMock();

        $resultSetMock->method('toArray')
            ->willReturn($this->mockedArticles);

        // SelectQueryのモックを作成
        /** @var SelectQuery&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'all'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'title', 'body', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('all')
            ->willReturn($resultSetMock);

        // ArticlesTableのモックを作成
        /** @var ArticlesTable&\PHPUnit\Framework\MockObject\MockObject $articlesTableMock */
        $articlesTableMock = $this->getMockBuilder(ArticlesTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMock();

        $articlesTableMock->method('find')
            ->willReturn($selectQueryMock);

        // テスト対象のServiceを作成
        $service = new ArticlesService($articlesTableMock);

        // メソッドを実行
        $result = $service->getArticles();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('articles', $result);
        $this->assertEquals($this->mockedArticles, $result['articles']);
    }

    /**
     * Test getArticles method with empty result
     *
     * @return void
     */
    public function testGetArticlesEmpty(): void
    {
        // ResultSetのモックを作成（空の配列を返す）
        /** @var ResultSet&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toArray'])
            ->getMock();

        $resultSetMock->method('toArray')
            ->willReturn([]);

        // SelectQueryのモックを作成
        /** @var SelectQuery&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'all'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'title', 'body', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('all')
            ->willReturn($resultSetMock);

        // ArticlesTableのモックを作成
        /** @var ArticlesTable&\PHPUnit\Framework\MockObject\MockObject $articlesTableMock */
        $articlesTableMock = $this->getMockBuilder(ArticlesTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMock();

        $articlesTableMock->method('find')
            ->willReturn($selectQueryMock);

        // テスト対象のServiceを作成
        $service = new ArticlesService($articlesTableMock);

        // メソッドを実行
        $result = $service->getArticles();

        // 結果を検証
        $this->assertIsArray($result);
        $this->assertArrayHasKey('articles', $result);
        $this->assertEmpty($result['articles']);
    }

    /**
     * Test getArticles method with database error
     *
     * @return void
     */
    public function testGetArticlesDatabaseError(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Database error occurred');

        // SelectQueryのモックを作成（例外を投げる）
        /** @var SelectQuery&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'all'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'title', 'body', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('all')
            ->willThrowException(new RuntimeException('Database error occurred'));

        // ArticlesTableのモックを作成
        /** @var ArticlesTable&\PHPUnit\Framework\MockObject\MockObject $articlesTableMock */
        $articlesTableMock = $this->getMockBuilder(ArticlesTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['find'])
            ->getMock();

        $articlesTableMock->method('find')
            ->willReturn($selectQueryMock);

        // テスト対象のServiceを作成
        $service = new ArticlesService($articlesTableMock);

        // メソッドを実行（例外が発生することを期待）
        $service->getArticles();
    }
}
