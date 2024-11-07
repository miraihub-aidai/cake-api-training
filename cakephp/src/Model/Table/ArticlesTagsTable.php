<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Table;

/**
 * ArticlesTagsTable クラス
 *
 * 記事とタグの関連付けを管理するテーブルクラスです。
 *
 * @package App\Model\Table
 */
class ArticlesTagsTable extends Table
{
    /**
     * 初期化メソッド
     *
     * テーブルの設定や関連付けを定義します。
     *
     * @param array<mixed> $config 設定配列
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // 必要に応じて設定を追加
        $this->setTable('articles_tags');
        $this->setPrimaryKey(['article_id', 'tag_id']);

        /**
         * Articles テーブルとの関連付け
         *
         * 外部キー 'article_id' を使用してArticlesテーブルとINNER JOINします。
         */
        $this->belongsTo('Articles', [
            'foreignKey' => 'article_id',
            'joinType' => 'INNER',
        ]);

        /**
         * Tags テーブルとの関連付け
         *
         * 外部キー 'tag_id' を使用してTagsテーブルとINNER JOINします。
         */
        $this->belongsTo('Tags', [
            'foreignKey' => 'tag_id',
            'joinType' => 'INNER',
        ]);
    }
}
