<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\Collection\Collection;
use Cake\ORM\Entity;

/**
 * Article エンティティ
 *
 * @property int $id
 * @property string $title
 * @property string $body
 * @property bool $published
 * @property \Cake\I18n\FrozenTime $created
 * @property \Cake\I18n\FrozenTime $modified
 * @property \App\Model\Entity\User[] $users
 * @property \App\Model\Entity\Tag[] $tags
 * @property string $tag_string
 */
class Article extends Entity
{
    /**
     * アクセス可能なプロパティのリスト
     *
     * @var array<string, bool> $_accessible
     */
    protected array $_accessible = [
        'title' => true,
        'body' => true,
        'published' => true,
        'created' => true,
        'modified' => true,
        'user_id' => true, // 追加
        'users' => true,
        'tag_string' => true,
    ];

    /**
     * タグ文字列を取得するための仮想プロパティ
     *
     * @return string タグをカンマ区切りで連結した文字列
     */
    protected function _getTagString(): string
    {
        if (isset($this->_fields['tag_string'])) {
            return $this->_fields['tag_string'];
        }
        if (empty($this->tags)) {
            return '';
        }
        $tags = new Collection($this->tags);
        $str = $tags->reduce(function ($string, $tag) {
            return $string . $tag->title . ', ';
        }, '');

        return trim($str, ', ');
    }
}
