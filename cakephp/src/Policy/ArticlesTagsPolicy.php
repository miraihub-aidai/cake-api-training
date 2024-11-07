<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\ArticlesTags;
use Authorization\IdentityInterface;

/**
 * ArticlesTagPolicy クラス
 *
 * ArticlesTags エンティティに対する権限管理を行うポリシークラスです。
 *
 * @package App\Policy
 */
class ArticlesTagsPolicy
{
    /**
     * 編集権限の確認
     *
     * 指定されたユーザーが特定の ArticlesTags エンティティを編集できるかどうかを判断します。
     *
     * @param \Authorization\IdentityInterface $user ユーザーのアイデンティティ
     * @param \App\Model\Entity\ArticlesTags $articlesTag 対象の ArticlesTags エンティティ
     * @return bool 編集が許可されている場合は true、そうでない場合は false
     */
    public function canEdit(IdentityInterface $user, ArticlesTags $articlesTag): bool
    {
        // 中間テーブルに対する編集権限を定義
        // 必要に応じてロジックを追加
        return true; // または適切な条件
    }

    /**
     * 追加権限の確認
     *
     * 指定されたユーザーが新しい ArticlesTags エンティティを追加できるかどうかを判断します。
     *
     * @param \Authorization\IdentityInterface $user ユーザーのアイデンティティ
     * @param \App\Model\Entity\ArticlesTags $articlesTag 新規追加する ArticlesTags エンティティ
     * @return bool 追加が許可されている場合は true、そうでない場合は false
     */
    public function canAdd(IdentityInterface $user, ArticlesTags $articlesTag): bool
    {
        return true; // または適切な条件
    }

    /**
     * 削除権限の確認
     *
     * 指定されたユーザーが特定の ArticlesTags エンティティを削除できるかどうかを判断します。
     *
     * @param \Authorization\IdentityInterface $user ユーザーのアイデンティティ
     * @param \App\Model\Entity\ArticlesTags $articlesTag 対象の ArticlesTags エンティティ
     * @return bool 削除が許可されている場合は true、そうでない場合は false
     */
    public function canDelete(IdentityInterface $user, ArticlesTags $articlesTag): bool
    {
        return true; // または適切な条件
    }
}
