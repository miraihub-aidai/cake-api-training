<?php
declare(strict_types=1);

use Migrations\AbstractSeed;

/**
 * InitialSeeder seed.
 */
class InitialSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeds is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     *
     * @return void
     */
    public function run(): void
    {
        // Insert into users
        $this->table('users')
            ->insert([
                'email' => 'cakephp@example.com',
                'password' => 'secret',
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ])
            ->saveData();

        // Insert into articles
        $this->table('articles')
            ->insert([
                'user_id' => 1,
                'title' => 'First Post',
                'slug' => 'first-post',
                'body' => 'This is the first post.',
                'published' => true,
                'created' => date('Y-m-d H:i:s'),
                'modified' => date('Y-m-d H:i:s')
            ])
            ->saveData();
    }
}
