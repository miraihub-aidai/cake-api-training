<?php
declare(strict_types=1);

namespace App\ServiceProvider;

use App\Domain\Interface\ArticlesInterface;
use App\Domain\UseCase\DeleteArticles;
use App\Domain\UseCase\GetArticles;
use App\Domain\UseCase\GetArticlesById;
use App\Domain\UseCase\PostArticles;
use App\Domain\UseCase\PutArticles;
use App\Model\Table\ArticlesTable;
use App\Service\ArticlesService;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\ORM\Locator\TableLocator;

/**
 * サービスプロバイダークラス
 * アプリケーションの依存性注入の設定を行います
 */
class ArticlesServiceProvider extends ServiceProvider
{
    protected array $provides = [
        // ここにプロバイダーが提供するサービスの識別子を列挙
        GetArticles::class,
        GetArticlesById::class,
        PostArticles::class,
        PutArticles::class,
        DeleteArticles::class,
        ArticlesInterface::class,
        ArticlesTable::class,
    ];

    /**
     * サービスを登録します
     *
     * @param \Cake\Core\ContainerInterface $container サービスコンテナ
     * @return void
     */
    public function services(ContainerInterface $container): void
    {
        // ArticlesTable の登録
        $container->add(ArticlesTable::class, function () {
            return (new TableLocator())->get('Articles');
        })->setShared(true);

        // Service の登録
        $container->add(ArticlesInterface::class, function () use ($container) {
            return new ArticlesService(
                $container->get(ArticlesTable::class)
            );
        })->setShared(true);

        // UseCase の登録
        $container->add(GetArticles::class, function () use ($container) {
            return new GetArticles($container->get(ArticlesInterface::class));
        })->setShared(true);

        // GetArticlesById の登録
        $container->add(GetArticlesById::class, function (ContainerInterface $container) {
            return new GetArticlesById($container->get(ArticlesInterface::class));
        })->setShared(true);

        // PostArticles の登録
        $container->add(PostArticles::class, function (ContainerInterface $container) {
            return new PostArticles($container->get(ArticlesInterface::class));
        })->setShared(true);

        // PutArticles の登録
        $container->add(PutArticles::class, function (ContainerInterface $container) {
            return new PutArticles($container->get(ArticlesInterface::class));
        })->setShared(true);

        // DeleteArticles の登録
        $container->add(DeleteArticles::class, function (ContainerInterface $container) {
            return new DeleteArticles($container->get(ArticlesInterface::class));
        })->setShared(true);
    }
}
