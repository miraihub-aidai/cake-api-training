<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ArticlesTags エンティティ
 *
 * 記事とタグの関連付けを表すエンティティクラスです。
 *
 * @package App\Model\Entity
 */
class ArticlesTags extends Entity
{
    /**
     * アクセス可能なフィールドの定義
     *
     * マスアサインメント（大量割り当て）を許可するフィールドを定義します。
     * これにより、指定されたフィールドのみがフォームからの入力などで一括して
     * 設定されることを許可されます。
     *
     * @var array<string, bool>
     */
    protected array $_accessible = [
        'article_id' => true, // 記事IDへのアクセスを許可
        'tag_id' => true, // タグIDへのアクセスを許可
        'article' => true, // 関連する記事エンティティへのアクセスを許可
        'tag' => true, // 関連するタグエンティティへのアクセスを許可
    ];
}
