<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateArticles extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * https://book.cakephp.org/phinx/0/en/migrations.html#the-change-method
     * @return void
     */
    public function change(): void
    {
        $table = $this->table('articles');
        $table->addColumn('user_id', 'integer', ['null' => false])
              ->addColumn('title', 'string', ['limit' => 255, 'null' => false])
              ->addColumn('slug', 'string', ['limit' => 191, 'null' => false])
              ->addColumn('body', 'text', ['null' => true])
              ->addColumn('published', 'boolean', ['default' => false])
              ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('modified', 'timestamp', ['default' => 'CURRENT_TIMESTAMP', 'update' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['slug'], ['unique' => true])
              ->addForeignKey('user_id', 'users', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->create();
    }
}
