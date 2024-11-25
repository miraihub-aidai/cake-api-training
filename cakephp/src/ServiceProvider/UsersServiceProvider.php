<?php
declare(strict_types=1);

namespace App\ServiceProvider;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\DeleteUsers;
use App\Domain\UseCase\GetUsers;
use App\Domain\UseCase\GetUsersById;
use App\Domain\UseCase\PostUsers;
use App\Domain\UseCase\PutUsers;
use App\Model\Table\UsersTable;
use App\Service\UsersService;
use Cake\Core\ContainerInterface;
use Cake\Core\ServiceProvider;
use Cake\ORM\Locator\TableLocator;

/**
 * サービスプロバイダークラス
 * アプリケーションの依存性注入の設定を行います
 */
class UsersServiceProvider extends ServiceProvider
{
    protected array $provides = [
        // ここにプロバイダーが提供するサービスの識別子を列挙
        GetUsers::class,
        GetUsersById::class,
        PostUsers::class,
        PutUsers::class,
        DeleteUsers::class,
        UsersInterface::class,
        UsersTable::class,
    ];

    /**
     * サービスを登録します
     *
     * @param \Cake\Core\ContainerInterface $container サービスコンテナ
     * @return void
     */
    public function services(ContainerInterface $container): void
    {
        // UsersTable の登録
        $container->add(UsersTable::class, function () {
            return (new TableLocator())->get('Users');
        })->setShared(true);

        // Service の登録
        $container->add(UsersInterface::class, function () use ($container) {
            return new UsersService(
                $container->get(UsersTable::class)
            );
        })->setShared(true);

        // UseCase の登録
        $container->add(GetUsers::class, function () use ($container) {
            return new GetUsers($container->get(UsersInterface::class));
        })->setShared(true);

        // GetUsersById の登録
        $container->add(GetUsersById::class, function (ContainerInterface $container) {
            return new GetUsersById($container->get(UsersInterface::class));
        })->setShared(true);

        // PostUsers の登録
        $container->add(PostUsers::class, function (ContainerInterface $container) {
            return new PostUsers($container->get(UsersInterface::class));
        })->setShared(true);

        // PutUsers の登録
        $container->add(PutUsers::class, function (ContainerInterface $container) {
            return new PutUsers($container->get(UsersInterface::class));
        })->setShared(true);

        // DeleteUsers の登録
        $container->add(DeleteUsers::class, function (ContainerInterface $container) {
            return new DeleteUsers($container->get(UsersInterface::class));
        })->setShared(true);
    }
}
