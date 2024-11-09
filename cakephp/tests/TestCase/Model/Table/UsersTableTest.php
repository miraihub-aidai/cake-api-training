<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\UsersTable;
use Cake\TestSuite\TestCase;

class UsersTableTest extends TestCase
{
    protected UsersTable $Users;

    protected array $fixtures = [
        'app.Users',
        'app.Articles',
    ];

    protected function setUp(): void
    {
        parent::setUp();
        $this->Users = $this->fetchTable('Users');
    }

    /**
     * Test validationDefault method
     */
    public function testValidationDefault(): void
    {
        // 正常系テスト
        $user = $this->Users->newEmptyEntity();
        $data = [
            'email' => 'test@example.com',
            'password' => 'password123',
        ];
        $user = $this->Users->patchEntity($user, $data);
        $this->assertEmpty($user->getErrors());

        // メールアドレス必須チェック
        $user = $this->Users->newEmptyEntity();
        $data = [
            'password' => 'password123',
        ];
        $user = $this->Users->patchEntity($user, $data);
        $this->assertNotEmpty($user->getErrors());
        $this->assertArrayHasKey('email', $user->getErrors());

        // 無効なメールアドレス形式
        $user = $this->Users->newEmptyEntity();
        $data = [
            'email' => 'invalid-email',
            'password' => 'password123',
        ];
        $user = $this->Users->patchEntity($user, $data);
        $this->assertNotEmpty($user->getErrors());
        $this->assertArrayHasKey('email', $user->getErrors());

        // パスワード必須チェック
        $user = $this->Users->newEmptyEntity();
        $data = [
            'email' => 'test@example.com',
        ];
        $user = $this->Users->patchEntity($user, $data);
        $this->assertNotEmpty($user->getErrors());
        $this->assertArrayHasKey('password', $user->getErrors());

        // メールアドレスの一意性チェック
        $user = $this->Users->newEmptyEntity();
        $data = [
            'email' => 'user@example.com', // フィクスチャに存在するメールアドレス
            'password' => 'password123',
        ];
        $user = $this->Users->patchEntity($user, $data);
        $result = $this->Users->save($user);
        $this->assertFalse($result);
        $this->assertArrayHasKey('email', $user->getErrors());
    }

    /**
     * Test buildRules method
     */
    public function testBuildRules(): void
    {
        // 新規ユーザーの作成（一意なメールアドレス）
        $user = $this->Users->newEmptyEntity();
        $data = [
            'email' => 'unique@example.com',
            'password' => 'password123',
        ];
        $user = $this->Users->patchEntity($user, $data);
        $result = $this->Users->save($user);
        $this->assertNotFalse($result);

        // 重複するメールアドレスでの作成を試みる
        $duplicate = $this->Users->newEmptyEntity();
        $data = [
            'email' => 'unique@example.com', // 直前に作成したメールアドレス
            'password' => 'another_password',
        ];
        $duplicate = $this->Users->patchEntity($duplicate, $data);
        $result = $this->Users->save($duplicate);
        $this->assertFalse($result);
    }

    /**
     * Test hasMany Articles association
     */
    public function testArticlesAssociation(): void
    {
        $user = $this->Users->get(1, contain: ['Articles']);

        // Articlesアソシエーションの確認
        $this->assertInstanceOf('Cake\ORM\Association\HasMany', $this->Users->Articles);

        // ユーザーに関連する記事が取得できることを確認
        $this->assertNotEmpty($user->articles);
        $this->assertInstanceOf('App\Model\Entity\Article', $user->articles[0]);
    }

    /**
     * Test beforeSave callback for password hashing
     */
    public function testBeforeSavePasswordHashing(): void
    {
        $user = $this->Users->newEmptyEntity();
        $data = [
            'email' => 'hash@example.com',
            'password' => 'newpassword123',
        ];
        $user = $this->Users->patchEntity($user, $data);
        $this->Users->save($user);

        // パスワードがハッシュ化されていることを確認
        $savedUser = $this->Users->get($user->id);
        $this->assertNotEquals($data['password'], $savedUser->password);
        $this->assertNotEmpty($savedUser->password);
    }
}
