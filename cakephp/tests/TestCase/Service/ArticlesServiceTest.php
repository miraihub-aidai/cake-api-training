<?php
declare(strict_types=1);

namespace App\Test\TestCase\Service;

use App\Model\Table\ArticlesTable;
use App\Service\ArticlesService;
use Cake\Datasource\EntityInterface;
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

    /**
     * Test getArticleById method
     *
     * @return void
     */
    public function testGetArticleById(): void
    {
        // ResultSetのモックを作成
        /** @var ResultSet<array<string, mixed>>&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);
        $entityMock->method('toArray')
            ->willReturn($this->mockedArticles[0]);

        $resultSetMock->method('first')
            ->willReturn($entityMock);

        // SelectQueryのモックを作成
        /** @var SelectQuery<array<string, mixed>>&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'where'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'title', 'body', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('where')
            ->with(['id' => 1])
            ->willReturn($resultSetMock);

        // ArticlesTableのfindメソッドのモックを設定
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
        $result = $service->getArticlesById('1'); // 引数を文字列に変更

        // 結果を検証
        $this->assertEquals($this->mockedArticles[0], $result);
    }

    /**
     * Test getArticlesById method with empty result
     *
     * @return void
     */
    public function testGetArticlesByIdEmpty(): void
    {
        // ResultSetのモックを作成（nullを返す）
        /** @var ResultSet<array<string, mixed>>&\PHPUnit\Framework\MockObject\MockObject $resultSetMock */
        $resultSetMock = $this->getMockBuilder(ResultSet::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['first'])
            ->getMock();

        $resultSetMock->method('first')
            ->willReturn(null);

        // SelectQueryのモックを作成
        /** @var SelectQuery<array<string, mixed>>&\PHPUnit\Framework\MockObject\MockObject $selectQueryMock */
        $selectQueryMock = $this->getMockBuilder(SelectQuery::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['select', 'where'])
            ->getMock();

        $selectQueryMock->method('select')
            ->with(['id', 'title', 'body', 'created', 'modified'])
            ->willReturnSelf();

        $selectQueryMock->method('where')
            ->with(['id' => 1])
            ->willReturn($resultSetMock);

        // ArticlesTableのfindメソッドのモックを設定
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
        $result = $service->getArticlesById('1'); // 引数を文字列に変更

        // 結果を検証
        $this->assertNull($result);
    }

    /**
     * Test postArticles method
     *
     * @return void
     */
    public function testPostArticles(): void
    {
        // ArticlesTableのsaveメソッドのモックを設定
        /** @var ArticlesTable&\PHPUnit\Framework\MockObject\MockObject $articlesTableMock */
        $articlesTableMock = $this->getMockBuilder(ArticlesTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['newEntity', 'save'])
            ->getMock();

        $newArticleData = [
            'title' => 'New Article',
            'body' => 'Content of the new article',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ];

        $savedArticleData = $newArticleData + ['id' => 1];

        $entityMock = $this->createMock(EntityInterface::class);
        $entityMock->method('toArray')
            ->willReturn($savedArticleData);

        $articlesTableMock->method('newEntity')
            ->with($newArticleData)
            ->willReturn($entityMock); // モックの戻り値の型を修正

        $articlesTableMock->method('save')
            ->with($entityMock) // モックの戻り値の型を修正
            ->willReturn($entityMock); // モックの戻り値の型を修正

        // テスト対象のServiceを作成
        $service = new ArticlesService($articlesTableMock);

        // メソッドを実行
        $result = $service->postArticles($newArticleData);

        // 結果を検証
        $this->assertEquals($savedArticleData, $result);
    }

    /**
     * Test postArticles method with save failure
     *
     * @return void
     */
    public function testPostArticlesSaveFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to save article');

        // ArticlesTableのsaveメソッドのモックを設定
        /** @var ArticlesTable&\PHPUnit\Framework\MockObject\MockObject $articlesTableMock */
        $articlesTableMock = $this->getMockBuilder(ArticlesTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['newEntity', 'save'])
            ->getMock();

        $newArticleData = [
            'title' => 'New Article',
            'body' => 'Content of the new article',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ];

        $entityMock = $this->createMock(EntityInterface::class);

        $articlesTableMock->method('newEntity')
            ->with($newArticleData)
            ->willReturn($entityMock); // モックの戻り値の型を修正

        $articlesTableMock->method('save')
            ->with($entityMock) // モックの戻り値の型を修正
            ->willReturn(false);

        // テスト対象のServiceを作成
        $service = new ArticlesService($articlesTableMock);

        // メソッドを実行（例外が発生することを期待）
        $service->postArticles($newArticleData);
    }

    /**
     * Test putArticles method
     *
     * @return void
     */
    public function testPutArticles(): void
    {
        // ArticlesTableのget, patchEntity, saveメソッドのモックを設定
        /** @var ArticlesTable&\PHPUnit\Framework\MockObject\MockObject $articlesTableMock */
        $articlesTableMock = $this->getMockBuilder(ArticlesTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'patchEntity', 'save'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);
        $entityMock->method('toArray')
            ->willReturn($this->mockedArticles[0]);

        $articlesTableMock->method('get')
            ->willReturn($entityMock); // モックの戻り値の型を修正

        $articlesTableMock->method('patchEntity')
            ->willReturn($entityMock); // モックの戻り値の型を修正

        $articlesTableMock->method('save')
            ->willReturn($entityMock); // モックの戻り値の型を修正

        // テスト対象のServiceを作成
        $service = new ArticlesService($articlesTableMock);

        // メソッドを実行
        $result = $service->putArticles('1', ['title' => 'Updated Article', 'body' => 'Updated Content']); // 引数を文字列に変更

        // 結果を検証
        $this->assertEquals($this->mockedArticles[0], $result);
    }

    /**
     * Test putArticles method with save failure
     *
     * @return void
     */
    public function testPutArticlesSaveFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to update article');

        // ArticlesTableのget, patchEntity, saveメソッドのモックを設定
        /** @var ArticlesTable&\PHPUnit\Framework\MockObject\MockObject $articlesTableMock */
        $articlesTableMock = $this->getMockBuilder(ArticlesTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'patchEntity', 'save'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);

        $articlesTableMock->method('get')
            ->willReturn($entityMock); // モックの戻り値の型を修正

        $articlesTableMock->method('patchEntity')
            ->willReturn($entityMock); // モックの戻り値の型を修正

        $articlesTableMock->method('save')
            ->willReturn(false);

        // テスト対象のServiceを作成
        $service = new ArticlesService($articlesTableMock);

        // メソッドを実行（例外が発生することを期待）
        $service->putArticles('1', ['title' => 'Updated Article', 'body' => 'Updated Content']); // 引数を文字列に変更
    }

    /**
     * Test deleteArticles method
     *
     * @return void
     */
    public function testDeleteArticles(): void
    {
        // ArticlesTableのget, deleteメソッドのモックを設定
        /** @var ArticlesTable&\PHPUnit\Framework\MockObject\MockObject $articlesTableMock */
        $articlesTableMock = $this->getMockBuilder(ArticlesTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);

        $articlesTableMock->method('get')
            ->willReturn($entityMock); // モックの戻り値の型を修正

        $articlesTableMock->method('delete')
            ->willReturn(true);

        // テスト対象のServiceを作成
        $service = new ArticlesService($articlesTableMock);

        // メソッドを実行
        $result = $service->deleteArticles('1'); // 引数を文字列に変更

        // 結果を検証
        $this->assertTrue($result);
    }

    /**
     * Test deleteArticles method with delete failure
     *
     * @return void
     */
    public function testDeleteArticlesFailure(): void
    {
        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage('Unable to delete article');

        // ArticlesTableのget, deleteメソッドのモックを設定
        /** @var ArticlesTable&\PHPUnit\Framework\MockObject\MockObject $articlesTableMock */
        $articlesTableMock = $this->getMockBuilder(ArticlesTable::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['get', 'delete'])
            ->getMock();

        $entityMock = $this->createMock(EntityInterface::class);

        $articlesTableMock->method('get')
            ->willReturn($entityMock); // モックの戻り値の型を修正

        $articlesTableMock->method('delete')
            ->willReturn(false);

        // テスト対象のServiceを作成
        $service = new ArticlesService($articlesTableMock);

        // メソッドを実行（例外が発生することを期待）
        $service->deleteArticles('1'); // 引数を文字列に変更
    }
}
