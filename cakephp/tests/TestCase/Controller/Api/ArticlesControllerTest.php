<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\DeleteArticles;
use App\Domain\UseCase\GetArticles;
use App\Domain\UseCase\GetArticlesById;
use App\Domain\UseCase\PostArticles;
use App\Domain\UseCase\PutArticles;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;
use JsonException;

class ArticlesControllerTest extends TestCase
{
    use IntegrationTestTrait;

    /**
     * @var array
     */
    protected array $mockedArticles = [
        [
            'id' => 1,
            'title' => 'Test Article 1',
            'content' => 'Content 1',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ],
        [
            'id' => 2,
            'title' => 'Test Article 2',
            'content' => 'Content 2',
            'created' => '2024-01-02 00:00:00',
            'modified' => '2024-01-02 00:00:00',
        ],
    ];

    /**
     * Setup before each test
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        // ルーティングをリセット
        Router::reload();

        // ルーティングを設定
        Router::createRouteBuilder('/')
            ->setExtensions(['json'])
            ->scope('/api', function (RouteBuilder $builder) {
                $builder->setRouteClass(DashedRoute::class);
                $builder->connect('/articles', [
                    'prefix' => 'Api',
                    'controller' => 'Articles',
                    'action' => 'getArticles',
                    '_method' => 'GET',
                ])->setMethods(['GET']);

                // GET /articles/:id - 特定の記事取得
                $builder->connect('/articles/:id', [
                    'prefix' => 'Api',
                    'controller' => 'Articles',
                    'action' => 'getArticlesById',
                    '_method' => 'GET',
                ])->setMethods(['GET']);

                // POST /articles - 記事作成
                $builder->connect('/articles', [
                    'prefix' => 'Api',
                    'controller' => 'Articles',
                    'action' => 'PostArticles',
                    '_method' => 'POST',
                ])->setMethods(['POST']);

                // PUT /articles/:id - 記事更新
                $builder->connect('/articles/:id', [
                    'prefix' => 'Api',
                    'controller' => 'Articles',
                    'action' => 'putArticles',
                    '_method' => 'PUT',
                ])->setMethods(['PUT']);

                // DELETE /articles/:id - 記事削除
                $builder->connect('/articles/:id', [
                    'prefix' => 'Api',
                    'controller' => 'Articles',
                    'action' => 'deleteArticles',
                    '_method' => 'DELETE',
                ])->setMethods(['DELETE']);
            });

        // リクエストの設定
        $this->configRequest([
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
        ]);

        // CSRFチェックを無効化
        $this->disableErrorHandlerMiddleware();
    }

    /**
     * Test getArticles method
     *
     * @return void
     */
    public function testGetArticles(): void
    {
        // ArticlesServiceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        $articlesServiceMock->method('getArticles')
            ->willReturn(['articles' => $this->mockedArticles]);

        // GetArticlesのインスタンスを作成
        $getArticles = new GetArticles($articlesServiceMock);

        // GetArticlesをDIコンテナに登録
        $this->mockService(GetArticles::class, function () use ($getArticles) {
            return $getArticles;
        });

        // リクエストを実行
        $this->get('/api/articles');

        // レスポンスを検証
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        // レスポンスボディを検証
        $expectedJson = json_encode(['articles' => $this->mockedArticles]);
        $this->assertEquals($expectedJson, (string)$this->_response->getBody());
    }

    /**
     * Test getArticles method with empty result
     *
     * @return void
     */
    public function testGetArticlesEmpty(): void
    {
        // ArticlesServiceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        $articlesServiceMock->method('getArticles')
            ->willReturn(['articles' => []]);

        // GetArticlesのインスタンスを作成
        $getArticles = new GetArticles($articlesServiceMock);

        // GetArticlesをDIコンテナに登録
        $this->mockService(GetArticles::class, function () use ($getArticles) {
            return $getArticles;
        });

        // リクエストを実行
        $this->get('/api/articles');

        // レスポンスを検証
        $this->assertResponseOk();
        $this->assertContentType('application/json');

        // レスポンスボディを検証
        $expectedJson = json_encode(['articles' => []]);
        $this->assertEquals($expectedJson, (string)$this->_response->getBody());
    }

    /**
     * Test getArticles method with invalid JSON
     *
     * @return void
     */
    public function testGetArticlesInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        // ArticlesServiceのモックを作成
        /** @var ArticlesInterface&\PHPUnit\Framework\MockObject\MockObject $articlesServiceMock */
        $articlesServiceMock = $this->getMockBuilder(ArticlesInterface::class)
            ->getMock();

        // 無限ループする参照を作成
        $invalidData = [];
        $invalidData['recursive'] = &$invalidData;

        $articlesServiceMock->method('getArticles')
            ->willReturn(['articles' => $invalidData]);

        // GetArticlesのインスタンスを作成
        $getArticles = new GetArticles($articlesServiceMock);

        // GetArticlesをDIコンテナに登録
        $this->mockService(GetArticles::class, function () use ($getArticles) {
            return $getArticles;
        });

        // リクエストを実行
        $this->get('/api/articles');
    }

    /**
     * Test GetArticlesById method
     *
     * @return void
     */
    public function testGetArticlesById(): void
    {
        // ArticlesServiceのモックを作成
        /** @var GetArticlesById&\PHPUnit\Framework\MockObject\MockObject $GetArticlesByIdMock */
        $GetArticlesByIdMock = $this->getMockBuilder(GetArticlesById::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['__invoke'])
            ->getMock();

        $articleData = [
            'id' => 1,
            'title' => 'Test Article 1',
            'body' => 'Content 1',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ];

        $GetArticlesByIdMock->method('__invoke')
            ->with('1')
            ->willReturn($articleData);

        // GetArticlesByIdをDIコンテナに登録
        $this->mockService(GetArticlesById::class, function () use ($GetArticlesByIdMock) {
            return $GetArticlesByIdMock;
        });

        // リクエストを実行
        $this->get('/api/articles/1');

        // 結果を検証
        $this->assertResponseOk();
        $this->assertContentType('application/json');
        $this->assertResponseContains('"id": 1');
        $this->assertResponseContains('"title": "Test Article 1"');
        $this->assertResponseContains('"body": "Content 1"');
    }

    /**
     * Test GetArticlesById method with invalid JSON
     *
     * @return void
     */
    public function testGetArticlesByIdInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        // ArticlesServiceのモックを作成
        /** @var GetArticlesById&\PHPUnit\Framework\MockObject\MockObject $GetArticlesByIdMock */
        $GetArticlesByIdMock = $this->getMockBuilder(GetArticlesById::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['__invoke'])
            ->getMock();

        // 無限ループする参照を作成
        $invalidData = [];
        $invalidData['recursive'] = &$invalidData;

        $GetArticlesByIdMock->method('__invoke')
            ->with('1')
            ->willReturn($invalidData);

        // GetArticlesByIdをDIコンテナに登録
        $this->mockService(GetArticlesById::class, function () use ($GetArticlesByIdMock) {
            return $GetArticlesByIdMock;
        });

        // リクエストを実行
        $this->get('/api/articles/1');

        // 例外が発生することを検証
        $this->expectException(JsonException::class);
    }

    /**
     * Test PostArticles method
     *
     * @return void
     */
    public function testPostArticles(): void
    {
        // ArticlesServiceのモックを作成
        /** @var PostArticles&\PHPUnit\Framework\MockObject\MockObject $PostArticlesMock */
        $PostArticlesMock = $this->getMockBuilder(PostArticles::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['__invoke'])
            ->getMock();

        $newArticleData = [
            'title' => 'New Article',
            'body' => 'Content of the new article',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ];

        $savedArticleData = $newArticleData + ['id' => 1];

        $PostArticlesMock->method('__invoke')
            ->with($newArticleData)
            ->willReturn($savedArticleData);

        // PostArticlesをDIコンテナに登録
        $this->mockService(PostArticles::class, function () use ($PostArticlesMock) {
            return $PostArticlesMock;
        });

        // リクエストを実行
        $this->post('/api/articles', $newArticleData);

        // 結果を検証
        $this->assertResponseSuccess();
        $this->assertContentType('application/json');
        $this->assertResponseContains('"title": "New Article"');
        $this->assertResponseContains('"body": "Content of the new article"');
        $this->assertResponseContains('"created": "2024-01-01 00:00:00"');
        $this->assertResponseContains('"modified": "2024-01-01 00:00:00"');
        $this->assertResponseContains('"id": 1');
    }

    /**
     * Test PostArticles method with invalid JSON
     *
     * @return void
     */
    public function testPostArticlesInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        // ArticlesServiceのモックを作成
        /** @var PostArticles&\PHPUnit\Framework\MockObject\MockObject $PostArticlesMock */
        $PostArticlesMock = $this->getMockBuilder(PostArticles::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['__invoke'])
            ->getMock();

        // 無限ループする参照を作成
        $invalidData = [];
        $invalidData['recursive'] = &$invalidData;

        $PostArticlesMock->method('__invoke')
            ->with($invalidData)
            ->willReturn($invalidData);

        // PostArticlesをDIコンテナに登録
        $this->mockService(PostArticles::class, function () use ($PostArticlesMock) {
            return $PostArticlesMock;
        });

        // リクエストを実行
        $this->post('/api/articles', $invalidData);

        // 例外が発生することを検証
        $this->expectException(JsonException::class);
    }

    /**
     * Test putArticles method
     *
     * @return void
     */
    public function testPutArticles(): void
    {
        // ArticlesServiceのモックを作成
        /** @var PutArticles&\PHPUnit\Framework\MockObject\MockObject $putArticlesMock */
        $putArticlesMock = $this->getMockBuilder(PutArticles::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['__invoke'])
            ->getMock();

        $updatedArticleData = [
            'title' => 'Updated Article',
            'body' => 'Updated content of the article',
            'created' => '2024-01-01 00:00:00',
            'modified' => '2024-01-01 00:00:00',
        ];

        $savedArticleData = $updatedArticleData + ['id' => 1];

        $putArticlesMock->method('__invoke')
            ->with('1', $updatedArticleData)
            ->willReturn($savedArticleData);

        // PutArticlesをDIコンテナに登録
        $this->mockService(PutArticles::class, function () use ($putArticlesMock) {
            return $putArticlesMock;
        });

        // リクエストを実行
        $this->put('/api/articles/1', $updatedArticleData);

        // 結果を検証
        $this->assertResponseSuccess();
        $this->assertContentType('application/json');
        $this->assertResponseContains('"title": "Updated Article"');
        $this->assertResponseContains('"body": "Updated content of the article"');
        $this->assertResponseContains('"created": "2024-01-01 00:00:00"');
        $this->assertResponseContains('"modified": "2024-01-01 00:00:00"');
        $this->assertResponseContains('"id": 1');
    }

    /**
     * Test putArticles method with invalid JSON
     *
     * @return void
     */
    public function testPutArticlesInvalidJson(): void
    {
        $this->expectException(JsonException::class);

        // ArticlesServiceのモックを作成
        /** @var PutArticles&\PHPUnit\Framework\MockObject\MockObject $putArticlesMock */
        $putArticlesMock = $this->getMockBuilder(PutArticles::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['__invoke'])
            ->getMock();

        // 無限ループする参照を作成
        $invalidData = [];
        $invalidData['recursive'] = &$invalidData;

        $putArticlesMock->method('__invoke')
            ->with('1', $invalidData)
            ->willReturn($invalidData);

        // PutArticlesをDIコンテナに登録
        $this->mockService(PutArticles::class, function () use ($putArticlesMock) {
            return $putArticlesMock;
        });

        // リクエストを実行
        $this->put('/api/articles/1', $invalidData);

        // 例外が発生することを検証
        $this->expectException(JsonException::class);
    }

    /**
     * Test deleteArticles method
     *
     * @return void
     */
    public function testDeleteArticles(): void
    {
        // ArticlesServiceのモックを作成
        /** @var DeleteArticles&\PHPUnit\Framework\MockObject\MockObject $deleteArticlesMock */
        $deleteArticlesMock = $this->getMockBuilder(DeleteArticles::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['__invoke'])
            ->getMock();

        $deleteArticlesMock->method('__invoke')
            ->with('1')
            ->willReturn(true);

        // DeleteArticlesをDIコンテナに登録
        $this->mockService(DeleteArticles::class, function () use ($deleteArticlesMock) {
            return $deleteArticlesMock;
        });

        // リクエストを実行
        $this->delete('/api/articles/1');

        // 結果を検証
        $this->assertResponseSuccess();
        $this->assertContentType('application/json');
        $this->assertResponseContains('"success": true');
    }

    /**
     * Tear down
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        Router::reload();
    }
}
