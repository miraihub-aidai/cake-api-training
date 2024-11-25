<?php
declare(strict_types=1);

namespace App\ServiceProvider;

use App\Domain\Interface\TagsInterface;
use App\Domain\UseCase\DeleteTags;
use App\Domain\UseCase\GetTags;
use App\Domain\UseCase\GetTagsById;
use App\Domain\UseCase\PostTags;
use App\Domain\UseCase\PutTags;
use App\Model\Table\TagsTable;
use App\Service\TagsService;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\ORM\Locator\TableLocator;

/**
 * サービスプロバイダークラス
 * アプリケーションの依存性注入の設定を行います
 */
class TagsServiceProvider extends ServiceProvider
{
    protected array $provides = [
        // ここにプロバイダーが提供するサービスの識別子を列挙
        GetTags::class,
        GetTagsById::class,
        PostTags::class,
        PutTags::class,
        DeleteTags::class,
        TagsInterface::class,
        TagsTable::class,
    ];

    /**
     * サービスを登録します
     *
     * @param \Cake\Core\ContainerInterface $container サービスコンテナ
     * @return void
     */
    public function services(ContainerInterface $container): void
    {
        // TagsTable の登録
        $container->add(TagsTable::class, function () {
            return (new TableLocator())->get('Tags');
        })->setShared(true);

        // Service の登録
        $container->add(TagsInterface::class, function () use ($container) {
            return new TagsService(
                $container->get(TagsTable::class)
            );
        })->setShared(true);

        // UseCase の登録
        $container->add(GetTags::class, function () use ($container) {
            return new GetTags($container->get(TagsInterface::class));
        })->setShared(true);

        // GetTagsById の登録
        $container->add(GetTagsById::class, function (ContainerInterface $container) {
            return new GetTagsById($container->get(TagsInterface::class));
        })->setShared(true);

        // PostTags の登録
        $container->add(PostTags::class, function (ContainerInterface $container) {
            return new PostTags($container->get(TagsInterface::class));
        })->setShared(true);

        // PutTags の登録
        $container->add(PutTags::class, function (ContainerInterface $container) {
            return new PutTags($container->get(TagsInterface::class));
        })->setShared(true);

        // DeleteTags の登録
        $container->add(DeleteTags::class, function (ContainerInterface $container) {
            return new DeleteTags($container->get(TagsInterface::class));
        })->setShared(true);
    }
}
