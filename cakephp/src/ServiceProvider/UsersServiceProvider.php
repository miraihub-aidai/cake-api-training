<?php
declare(strict_types=1);

namespace App\ServiceProvider;

use App\Domain\Interface\UsersInterface;
use App\Domain\UseCase\CreateUser;
use App\Domain\UseCase\DeleteUser;
use App\Domain\UseCase\GetUserById;
use App\Domain\UseCase\GetUsers;
use App\Domain\UseCase\PatchUser;
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
        GetUserById::class,
        CreateUser::class,
        PatchUser::class,
        DeleteUser::class,
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
        $container->add(GetUserById::class, function () use ($container) {
            return new GetUserById($container->get(UsersInterface::class));
        })->setShared(true);
        $container->add(CreateUser::class, function () use ($container) {
            return new CreateUser($container->get(UsersInterface::class));
        })->setShared(true);
        $container->add(PatchUser::class, function () use ($container) {
            return new PatchUser($container->get(UsersInterface::class));
        })->setShared(true);
        $container->add(DeleteUser::class, function () use ($container) {
            return new DeleteUser($container->get(UsersInterface::class));
        })->setShared(true);
    }
}
