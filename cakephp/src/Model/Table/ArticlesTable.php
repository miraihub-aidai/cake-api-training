<?php
declare(strict_types=1);

namespace App\Model\Table;

use ArrayObject;
use Cake\Event\EventInterface;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\Table;
use Cake\Utility\Text;
use Cake\Validation\Validator;

/**
 * ArticlesTable
 *
 * 記事テーブルを表すモデルクラス
 *
 * @property \App\Model\Table\TagsTable $Tags タグテーブルとの関連
 * @property \App\Model\Table\UsersTable $Users ユーザーテーブルとの関連
 * @method \Cake\ORM\SelectQuery findBySlug(string|null $slug)
 * @package App\Model\Table
 */
class ArticlesTable extends Table
{
    /**
     * テーブルの初期化メソッド
     *
     * @param array<mixed> $config 設定オプション
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        // Timestampビヘイビアを追加
        // これにより、created と modified フィールドが自動的に管理されます
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Tags', [
            'foreignKey' => 'article_id',
            'targetForeignKey' => 'tag_id',
            'joinTable' => 'articles_tags',
            'dependent' => true,
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
        ]);
    }

    /**
     * エンティティ保存前のコールバックメソッド
     *
     * 新規エンティティの場合、タイトルからスラグを自動生成します
     *
     * @param \Cake\Event\EventInterface $event イベントオブジェクト
     * @param mixed $entity 保存されるエンティティ
     * @param \ArrayObject $options 保存オプション
     * @return void
     */
    public function beforeSave(EventInterface $event, mixed $entity, ArrayObject $options): void
    {
        if ($entity->tag_string) {
            $entity->tags = $this->_buildTags($entity->tag_string);
        }

        if ($entity->isNew() && !$entity->slug) {
            $sluggedTitle = Text::slug($entity->title);
            // スラグをスキーマで定義されている最大長に調整
            $entity->slug = substr($sluggedTitle, 0, 191);
        }
    }

    /**
     * バリデーションルールを設定するメソッド
     *
     * @param \Cake\Validation\Validator $validator バリデーターインスタンス
     * @return \Cake\Validation\Validator 設定済みのバリデーターインスタンス
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->requirePresence('title', 'create', '記事のタイトルは必須です')
            ->minLength('title', 10, 'タイトルは最低10文字必要です')
            ->maxLength('title', 255, 'タイトルは255文字以内にしてください')

            ->notEmptyString('body', '記事の本文は必須です')
            ->minLength('body', 10, '本文は最低10文字必要です');

        return $validator;
    }

    /**
     * タグに基づいて記事を検索するカスタムファインダーメソッド
     *
     * @param \Cake\ORM\Query\SelectQuery $query クエリインスタンス
     * @param array<mixed> $options 検索オプション
     * @return \Cake\ORM\Query\SelectQuery 設定済みのクエリインスタンス
     */
    public function findTagged(SelectQuery $query, array $options): SelectQuery
    {
        $columns = [
            'Articles.id', 'Articles.user_id', 'Articles.title',
            'Articles.body', 'Articles.published', 'Articles.created',
            'Articles.slug',
        ];

        $query = $query
            ->select($columns)
            ->distinct($columns);

        if (empty($options['tags'])) {
            // タグが指定されていない場合は、タグのない記事を検索します。
            $query->leftJoinWith('Tags')
                ->where(['Tags.title IS' => null]);
        } else {
            // 提供されたタグが1つ以上ある記事を検索します。
            $query->innerJoinWith('Tags')
                ->where(['Tags.title IN' => $options['tags']]);
        }

        // Deprecation Warning: The group() method is deprecated. Use groupBy() instead.
        // return $query->group(['Articles.id']);
        return $query->groupBy(['Articles.id']);
    }

    /**
     * タグ文字列からタグエンティティの配列を構築するメソッド
     *
     * @param string $tagString カンマ区切りのタグ文字列
     * @return array<mixed> タグエンティティの配列
     */
    protected function _buildTags(string $tagString): array
    {
        // タグをトリミング
        $newTags = array_map('trim', explode(',', $tagString));
        // 全ての空のタグを削除
        $newTags = array_filter($newTags);
        // 重複するタグの削減
        $newTags = array_unique($newTags);

        $out = [];
        $query = $this->Tags->find()
            ->where(['Tags.title IN' => $newTags]);

        // 新しいタグのリストから既存のタグを削除
        // need all() to get all tags from the query
        foreach ($query->all()->extract('title') as $existing) {
            $index = array_search($existing, $newTags);
            if ($index !== false) {
                unset($newTags[$index]);
            }
        }
        // 既存のタグを追加
        foreach ($query as $tag) {
            $out[] = $tag;
        }
        // 新しいタグを追加
        foreach ($newTags as $tag) {
            $out[] = $this->Tags->newEntity(['title' => $tag]);
        }

        return $out;
    }
}
