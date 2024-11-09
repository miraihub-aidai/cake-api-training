<?php
declare(strict_types=1);

namespace App\Test\Fixture;

use Cake\TestSuite\Fixture\TestFixture;

/**
 * ArticlesFixture
 */
class ArticlesFixture extends TestFixture
{
    public function init(): void
    {
        $this->records = [
            [
                'id' => 1,
                'user_id' => 1,
                'title' => 'First Article With PHP',
                'slug' => 'first-article-with-php',
                'body' => 'First Article Body with more than 10 characters',
                'published' => true,
                'created' => '2024-01-01 10:00:00',
                'modified' => '2024-01-01 10:00:00',
            ],
            [
                'id' => 2,
                'user_id' => 1,
                'title' => 'Second Article Without Tags',
                'slug' => 'second-article-without-tags',
                'body' => 'Second Article Body with more than 10 characters',
                'published' => true,
                'created' => '2024-01-01 10:00:00',
                'modified' => '2024-01-01 10:00:00',
            ],
        ];
        parent::init();
    }
}
