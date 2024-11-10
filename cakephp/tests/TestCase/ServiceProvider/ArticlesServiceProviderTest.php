<?php
declare(strict_types=1);

namespace App\Test\TestCase\ServiceProvider;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\GetArticles;
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
