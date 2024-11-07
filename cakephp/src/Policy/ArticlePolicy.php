<?php
declare(strict_types=1);

namespace App\Policy;

use App\Model\Entity\Article;
use App\Model\Entity\User;
use Authorization\IdentityInterface;

/**
 * 記事ポリシークラス
 *
 * このクラスは、記事に関連する操作の認可ルールを定義します。
 *
 * @package App\Policy
 */
class ArticlePolicy
{
    /**
     * ユーザーが新しい記事を追加できるかどうかを判断します
     *
     * @param \Authorization\IdentityInterface $user 現在のユーザー
     * @param \App\Model\Entity\Article $article 対象の記事エンティティ
     * @return bool 記事の追加が許可される場合はtrue、そうでない場合はfalse
     */
    public function canAdd(IdentityInterface $user, Article $article): bool
    {
        // すべてのユーザーに記事の追加を許可
        return true;
    }

    /**
     * ユーザーが記事を編集できるかどうかを判断します
     *
     * @param \Authorization\IdentityInterface $user 現在のユーザー
     * @param \App\Model\Entity\Article $article 対象の記事エンティティ
     * @return bool 記事の編集が許可される場合はtrue、そうでない場合はfalse
     */
    public function canEdit(IdentityInterface $user, Article $article): bool
    {
        // ユーザーの元データを取得
        $originalData = $user->getOriginalData();

        // ユーザーがUserエンティティであることを確認
        if ($originalData instanceof User) {
            // 記事の所有者のみが編集可能
            return (int)$article->get('user_id') === (int)$originalData->id;
        }

        // ユーザー情報が不明な場合は編集不可
        return false;
    }

    /**
     * ユーザーが記事を削除できるかどうかを判断します
     *
     * @param \Authorization\IdentityInterface $user 現在のユーザー
     * @param \App\Model\Entity\Article $article 対象の記事エンティティ
     * @return bool 記事の削除が許可される場合はtrue、そうでない場合はfalse
     */
    public function canDelete(IdentityInterface $user, Article $article): bool
    {
        // 編集権限を持つユーザーのみが削除可能
        return $this->canEdit($user, $article);
    }
}
