<?php
declare(strict_types=1);

namespace App\Test\TestCase\ServiceProvider;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\DeleteArticles;
use App\Domain\UseCase\GetArticles;
use App\Domain\UseCase\GetArticlesById;
use App\Domain\UseCase\PostArticles;
use App\Domain\UseCase\PutArticles;
use App\Model\Table\ArticlesTable;
use App\Service\ArticlesService;
use App\ServiceProvider\ArticlesServiceProvider;
use Cake\Core\Container;
use Cake\TestSuite\TestCase;
use ReflectionProperty;

class ArticlesServiceProviderTest extends TestCase
{
    /**
     * @var \Cake\Core\Container
     */
    protected Container $container;

    /**
     * @var \App\ServiceProvider\ArticlesServiceProvider
     */
    protected ArticlesServiceProvider $provider;

    /**
     * Setup method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();
        $this->provider = new ArticlesServiceProvider();
    }

    /**
     * Test provider provides the correct services
     *
     * @return void
     */
    public function testProvides(): void
    {
        $expectedServices = [
            GetArticles::class,
            GetArticlesById::class,
            PostArticles::class,
            PutArticles::class,
            DeleteArticles::class,
            ArticlesInterface::class,
            ArticlesTable::class,
        ];

        // 各サービスがprovidesで提供されることを確認
        foreach ($expectedServices as $service) {
            $this->assertTrue(
                $this->provider->provides($service),
                "Provider should provide {$service}"
            );
        }

        // 存在しないサービスは提供されないことを確認
        $this->assertFalse(
            $this->provider->provides('NonExistentService'),
            'Provider should not provide non-existent service'
        );
    }

    /**
     * Test services registration
     *
     * @return void
     */
    public function testServices(): void
    {
        // サービスを登録
        $this->provider->services($this->container);

        // ArticlesTable が正しく解決されることを確認
        $articlesTable = $this->container->get(ArticlesTable::class);
        $this->assertInstanceOf(ArticlesTable::class, $articlesTable);

        // ArticlesService が正しく解決されることを確認
        $articlesService = $this->container->get(ArticlesInterface::class);
        $this->assertInstanceOf(ArticlesService::class, $articlesService);
        $this->assertInstanceOf(ArticlesInterface::class, $articlesService);

        // GetArticles が正しく解決されることを確認
        $getArticles = $this->container->get(GetArticles::class);
        $this->assertInstanceOf(GetArticles::class, $getArticles);

        // GetArticlesById が正しく解決されることを確認
        $getArticlesById = $this->container->get(GetArticlesById::class);
        $this->assertInstanceOf(GetArticlesById::class, $getArticlesById);

        // PostArticles が正しく解決されることを確認
        $postArticles = $this->container->get(PostArticles::class);
        $this->assertInstanceOf(PostArticles::class, $postArticles);

        // PutArticles が正しく解決されることを確認
        $putArticles = $this->container->get(PutArticles::class);
        $this->assertInstanceOf(PutArticles::class, $putArticles);

        // DeleteArticles が正しく解決されることを確認
        $deleteArticles = $this->container->get(DeleteArticles::class);
        $this->assertInstanceOf(DeleteArticles::class, $deleteArticles);
    }

    /**
     * Test services are shared (singleton)
     *
     * @return void
     */
    public function testServicesAreShared(): void
    {
        // サービスを登録
        $this->provider->services($this->container);

        // 同じインスタンスが返されることを確認
        $articlesTable1 = $this->container->get(ArticlesTable::class);
        $articlesTable2 = $this->container->get(ArticlesTable::class);
        $this->assertSame($articlesTable1, $articlesTable2);

        $articlesService1 = $this->container->get(ArticlesInterface::class);
        $articlesService2 = $this->container->get(ArticlesInterface::class);
        $this->assertSame($articlesService1, $articlesService2);

        $getArticles1 = $this->container->get(GetArticles::class);
        $getArticles2 = $this->container->get(GetArticles::class);
        $this->assertSame($getArticles1, $getArticles2);

         // GetArticlesByIdのシングルトン確認
         $getArticlesById1 = $this->container->get(GetArticlesById::class);
         $getArticlesById2 = $this->container->get(GetArticlesById::class);
         $this->assertSame($getArticlesById1, $getArticlesById2);

         // PostArticlesのシングルトン確認
         $postArticles1 = $this->container->get(PostArticles::class);
         $postArticles2 = $this->container->get(PostArticles::class);
         $this->assertSame($postArticles1, $postArticles2);

         // PutArticlesのシングルトン確認
         $putArticles1 = $this->container->get(PutArticles::class);
         $putArticles2 = $this->container->get(PutArticles::class);
         $this->assertSame($putArticles1, $putArticles2);

         // DeleteArticlesのシングルトン確認
         $deleteArticles1 = $this->container->get(DeleteArticles::class);
         $deleteArticles2 = $this->container->get(DeleteArticles::class);
         $this->assertSame($deleteArticles1, $deleteArticles2);
    }

    /**
     * Test dependency injection chain
     *
     * @return void
     */
    public function testDependencyInjectionChain(): void
    {
        // サービスを登録
        $this->provider->services($this->container);

        // GetArticles の依存関係チェーンを確認
        $getArticles = $this->container->get(GetArticles::class);

        // リフレクションを使用して protected プロパティにアクセス
        $articleServiceReflection = new ReflectionProperty(GetArticles::class, 'articlesService');
        $articleServiceReflection->setAccessible(true);
        $articlesService = $articleServiceReflection->getValue($getArticles);

        // ArticlesService の依存関係を確認
        $articlesTableReflection = new ReflectionProperty(ArticlesService::class, 'articles');
        $articlesTableReflection->setAccessible(true);
        $articlesTable = $articlesTableReflection->getValue($articlesService);

        // 型のチェック
        $this->assertInstanceOf(ArticlesService::class, $articlesService);
        $this->assertInstanceOf(ArticlesTable::class, $articlesTable);

        // GetArticlesById の依存関係チェーンを確認
        $getArticlesById = $this->container->get(GetArticlesById::class);

        $articleServiceReflection = new ReflectionProperty(GetArticlesById::class, 'articlesService');
        $articleServiceReflection->setAccessible(true);
        $articlesService = $articleServiceReflection->getValue($getArticlesById);

        $articlesTableReflection = new ReflectionProperty(ArticlesService::class, 'articles');
        $articlesTableReflection->setAccessible(true);
        $articlesTable = $articlesTableReflection->getValue($articlesService);

        $this->assertInstanceOf(ArticlesService::class, $articlesService);
        $this->assertInstanceOf(ArticlesTable::class, $articlesTable);

        // PostArticles の依存関係チェーンを確認
        $postArticles = $this->container->get(PostArticles::class);

        $articleServiceReflection = new ReflectionProperty(PostArticles::class, 'articlesService');
        $articleServiceReflection->setAccessible(true);
        $articlesService = $articleServiceReflection->getValue($postArticles);

        $articlesTableReflection = new ReflectionProperty(ArticlesService::class, 'articles');
        $articlesTableReflection->setAccessible(true);
        $articlesTable = $articlesTableReflection->getValue($articlesService);

        $this->assertInstanceOf(ArticlesService::class, $articlesService);
        $this->assertInstanceOf(ArticlesTable::class, $articlesTable);

        // PutArticles の依存関係チェーンを確認
        $putArticles = $this->container->get(PutArticles::class);

        $articleServiceReflection = new ReflectionProperty(PutArticles::class, 'articlesService');
        $articleServiceReflection->setAccessible(true);
        $articlesService = $articleServiceReflection->getValue($putArticles);

        $articlesTableReflection = new ReflectionProperty(ArticlesService::class, 'articles');
        $articlesTableReflection->setAccessible(true);
        $articlesTable = $articlesTableReflection->getValue($articlesService);

        $this->assertInstanceOf(ArticlesService::class, $articlesService);
        $this->assertInstanceOf(ArticlesTable::class, $articlesTable);

        // DeleteArticles の依存関係チェーンを確認
        $deleteArticles = $this->container->get(DeleteArticles::class);

        $articleServiceReflection = new ReflectionProperty(DeleteArticles::class, 'articlesService');
        $articleServiceReflection->setAccessible(true);
        $articlesService = $articleServiceReflection->getValue($deleteArticles);

        $articlesTableReflection = new ReflectionProperty(ArticlesService::class, 'articles');
        $articlesTableReflection->setAccessible(true);
        $articlesTable = $articlesTableReflection->getValue($articlesService);

        $this->assertInstanceOf(ArticlesService::class, $articlesService);
        $this->assertInstanceOf(ArticlesTable::class, $articlesTable);
    }

    /**
     * Tear down method
     *
     * @return void
     */
    public function tearDown(): void
    {
        parent::tearDown();
        unset($this->container, $this->provider);
    }
}
