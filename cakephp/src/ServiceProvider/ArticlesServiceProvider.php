<?php
declare(strict_types=1);

namespace App\ServiceProvider;

use App\Domain\Interface\ArticlesInterface;
use App\Service\ArticlesService;
use App\Domain\UseCase\GetArticles;
use App\Model\Table\ArticlesTable;
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
    }
}