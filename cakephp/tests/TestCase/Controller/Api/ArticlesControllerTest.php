<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller\Api;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\GetArticles;
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
