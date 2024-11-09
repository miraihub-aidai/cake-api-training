<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * UsersFixture
 */
class UsersFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'email' => 'user@example.com',
                'password' => '$2y$10$abcdefghijklmnopqrstuv', // ハッシュ化されたパスワード
                'created' => '2024-01-01 10:00:00',
                'modified' => '2024-01-01 10:00:00',
            ],
        ];
        parent::init();
    }
}
