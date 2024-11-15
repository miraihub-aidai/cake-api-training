<?php
declare(strict_types=1);

use Migrations\AbstractMigration;

class CreateArticlesTags extends AbstractMigration
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
        $table = $this->table('articles_tags', ['id' => false, 'primary_key' => ['article_id', 'tag_id']]);
        $table->addColumn('article_id', 'integer', ['null' => false])
              ->addColumn('tag_id', 'integer', ['null' => false])
              ->addForeignKey('article_id', 'articles', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->addForeignKey('tag_id', 'tags', 'id', ['delete' => 'CASCADE', 'update' => 'NO_ACTION'])
              ->create();
    }
}
