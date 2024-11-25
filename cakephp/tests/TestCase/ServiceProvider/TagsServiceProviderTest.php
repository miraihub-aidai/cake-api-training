<?php
declare(strict_types=1);

namespace App\Test\TestCase\ServiceProvider;

use App\Domain\Interface\TagsInterface;
use App\Domain\UseCase\DeleteTags;
use App\Domain\UseCase\GetTags;
use App\Domain\UseCase\GetTagsById;
use App\Domain\UseCase\PostTags;
use App\Domain\UseCase\PutTags;
use App\Model\Table\TagsTable;
use App\Service\TagsService;
use App\ServiceProvider\TagsServiceProvider;
use Cake\Core\Container;
use Cake\TestSuite\TestCase;
use ReflectionProperty;

class TagsServiceProviderTest extends TestCase
{
    /**
     * @var \Cake\Core\Container
     */
    protected Container $container;

    /**
     * @var \App\ServiceProvider\TagsServiceProvider
     */
    protected TagsServiceProvider $provider;

    /**
     * Setup method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->container = new Container();
        $this->provider = new TagsServiceProvider();
    }

    /**
     * Test provider provides the correct services
     *
     * @return void
     */
    public function testProvides(): void
    {
        $expectedServices = [
            GetTags::class,
            GetTagsById::class,
            PostTags::class,
            PutTags::class,
            DeleteTags::class,
            TagsInterface::class,
            TagsTable::class,
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

        // TagsTable が正しく解決されることを確認
        $tagsTable = $this->container->get(TagsTable::class);
        $this->assertInstanceOf(TagsTable::class, $tagsTable);

        // TagsService が正しく解決されることを確認
        $tagsService = $this->container->get(TagsInterface::class);
        $this->assertInstanceOf(TagsService::class, $tagsService);
        $this->assertInstanceOf(TagsInterface::class, $tagsService);

        // GetTags が正しく解決されることを確認
        $getTags = $this->container->get(GetTags::class);
        $this->assertInstanceOf(GetTags::class, $getTags);

        // GetTagsById が正しく解決されることを確認
        $getTagsById = $this->container->get(GetTagsById::class);
        $this->assertInstanceOf(GetTagsById::class, $getTagsById);

        // PostTags が正しく解決されることを確認
        $postTags = $this->container->get(PostTags::class);
        $this->assertInstanceOf(PostTags::class, $postTags);

        // PutTags が正しく解決されることを確認
        $putTags = $this->container->get(PutTags::class);
        $this->assertInstanceOf(PutTags::class, $putTags);

        // DeleteTags が正しく解決されることを確認
        $deleteTags = $this->container->get(DeleteTags::class);
        $this->assertInstanceOf(DeleteTags::class, $deleteTags);
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
        $tagsTable1 = $this->container->get(TagsTable::class);
        $tagsTable2 = $this->container->get(TagsTable::class);
        $this->assertSame($tagsTable1, $tagsTable2);

        $tagsService1 = $this->container->get(TagsInterface::class);
        $tagsService2 = $this->container->get(TagsInterface::class);
        $this->assertSame($tagsService1, $tagsService2);

        $getTags1 = $this->container->get(GetTags::class);
        $getTags2 = $this->container->get(GetTags::class);
        $this->assertSame($getTags1, $getTags2);

        // GetTagsByIdのシングルトン確認
        $getTagsById1 = $this->container->get(GetTagsById::class);
        $getTagsById2 = $this->container->get(GetTagsById::class);
        $this->assertSame($getTagsById1, $getTagsById2);

        // PostTagsのシングルトン確認
        $postTags1 = $this->container->get(PostTags::class);
        $postTags2 = $this->container->get(PostTags::class);
        $this->assertSame($postTags1, $postTags2);

        // PutTagsのシングルトン確認
        $putTags1 = $this->container->get(PutTags::class);
        $putTags2 = $this->container->get(PutTags::class);
        $this->assertSame($putTags1, $putTags2);

        // DeleteTagsのシングルトン確認
        $deleteTags1 = $this->container->get(DeleteTags::class);
        $deleteTags2 = $this->container->get(DeleteTags::class);
        $this->assertSame($deleteTags1, $deleteTags2);
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

        // GetTags の依存関係チェーンを確認
        $getTags = $this->container->get(GetTags::class);

        // リフレクションを使用して protected プロパティにアクセス
        $tagServiceReflection = new ReflectionProperty(GetTags::class, 'tagsService');
        $tagServiceReflection->setAccessible(true);
        $tagsService = $tagServiceReflection->getValue($getTags);

        // TagsService の依存関係を確認
        $tagsTableReflection = new ReflectionProperty(TagsService::class, 'tags');
        $tagsTableReflection->setAccessible(true);
        $tagsTable = $tagsTableReflection->getValue($tagsService);

        // 型のチェック
        $this->assertInstanceOf(TagsService::class, $tagsService);
        $this->assertInstanceOf(TagsTable::class, $tagsTable);

        // GetTagsById の依存関係チェーンを確認
        $getTagsById = $this->container->get(GetTagsById::class);

        $tagServiceReflection = new ReflectionProperty(GetTagsById::class, 'tagsService');
        $tagServiceReflection->setAccessible(true);
        $tagsService = $tagServiceReflection->getValue($getTagsById);

        $tagsTableReflection = new ReflectionProperty(TagsService::class, 'tags');
        $tagsTableReflection->setAccessible(true);
        $tagsTable = $tagsTableReflection->getValue($tagsService);

        $this->assertInstanceOf(TagsService::class, $tagsService);
        $this->assertInstanceOf(TagsTable::class, $tagsTable);

        // PostTags の依存関係チェーンを確認
        $postTags = $this->container->get(PostTags::class);

        $tagServiceReflection = new ReflectionProperty(PostTags::class, 'tagsService');
        $tagServiceReflection->setAccessible(true);
        $tagsService = $tagServiceReflection->getValue($postTags);

        $tagsTableReflection = new ReflectionProperty(TagsService::class, 'tags');
        $tagsTableReflection->setAccessible(true);
        $tagsTable = $tagsTableReflection->getValue($tagsService);

        $this->assertInstanceOf(TagsService::class, $tagsService);
        $this->assertInstanceOf(TagsTable::class, $tagsTable);

        // PutTags の依存関係チェーンを確認
        $putTags = $this->container->get(PutTags::class);

        $tagServiceReflection = new ReflectionProperty(PutTags::class, 'tagsService');
        $tagServiceReflection->setAccessible(true);
        $tagsService = $tagServiceReflection->getValue($putTags);

        $tagsTableReflection = new ReflectionProperty(TagsService::class, 'tags');
        $tagsTableReflection->setAccessible(true);
        $tagsTable = $tagsTableReflection->getValue($tagsService);

        $this->assertInstanceOf(TagsService::class, $tagsService);
        $this->assertInstanceOf(TagsTable::class, $tagsTable);

        // DeleteTags の依存関係チェーンを確認
        $deleteTags = $this->container->get(DeleteTags::class);

        $tagServiceReflection = new ReflectionProperty(DeleteTags::class, 'tagsService');
        $tagServiceReflection->setAccessible(true);
        $tagsService = $tagServiceReflection->getValue($deleteTags);

        $tagsTableReflection = new ReflectionProperty(TagsService::class, 'tags');
        $tagsTableReflection->setAccessible(true);
        $tagsTable = $tagsTableReflection->getValue($tagsService);

        $this->assertInstanceOf(TagsService::class, $tagsService);
        $this->assertInstanceOf(TagsTable::class, $tagsTable);
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
