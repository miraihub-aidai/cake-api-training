<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\TagsTable;
use Cake\TestSuite\TestCase;

class TagsTableTest extends TestCase
{
    /**
     * @var TagsTable $Tags TagsTable
     */
    protected TagsTable $Tags;

    /**
     * @var array $fixtures fixtures
     */
    protected array $fixtures = [
        'app.Articles',
        'app.Tags',
        'app.ArticlesTags',
        'app.Users',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->Tags = $this->fetchTable('Tags');
    }

    /**
     * Test validationDefault method
     */
    public function testValidationDefault(): void
    {
        // 正常系テスト
        $tag = $this->Tags->newEmptyEntity();
        $data = [
            'title' => 'New Tag',
        ];
        $tag = $this->Tags->patchEntity($tag, $data);
        $this->assertEmpty($tag->getErrors());

        // タイトルの長さ制限テスト (191文字超過)
        $tag = $this->Tags->newEmptyEntity();
        $data = [
            'title' => str_repeat('a', 192),
        ];
        $tag = $this->Tags->patchEntity($tag, $data);
        $this->assertNotEmpty($tag->getErrors());
        $this->assertArrayHasKey('title', $tag->getErrors());

        // 一意性のテスト
        $tag = $this->Tags->newEmptyEntity();
        $data = [
            'title' => 'PHP', // フィクスチャに存在するタグ
        ];
        $tag = $this->Tags->patchEntity($tag, $data);
        $result = $this->Tags->save($tag);
        $this->assertFalse($result);
        $this->assertNotEmpty($tag->getErrors());
        $this->assertArrayHasKey('title', $tag->getErrors());
    }

    /**
     * Test buildRules method
     */
    public function testBuildRules(): void
    {
        // 一意性ルールのテスト
        $tag = $this->Tags->newEmptyEntity();
        $data = [
            'title' => 'PHP', // 既存のタグ
        ];
        $tag = $this->Tags->patchEntity($tag, $data);

        $result = $this->Tags->save($tag);
        $this->assertFalse($result);

        // 新しいユニークなタグは保存可能
        $tag = $this->Tags->newEmptyEntity();
        $data = [
            'title' => 'Unique New Tag',
        ];
        $tag = $this->Tags->patchEntity($tag, $data);

        $result = $this->Tags->save($tag);
        $this->assertNotFalse($result);
    }

    /**
     * Test initialize method
     */
    public function testInitialize(): void
    {
        // Timestampビヘイビアのテスト
        $tag = $this->Tags->newEmptyEntity();
        $data = [
            'title' => 'Testing Initialize',
        ];
        $tag = $this->Tags->patchEntity($tag, $data);

        $this->Tags->save($tag);
        $this->assertNotEmpty($tag->created);
        $this->assertNotEmpty($tag->modified);

        // ArticlesとのBelongsToMany関連のテスト
        $tag = $this->Tags->get($tag->id, contain: ['Articles']);
        $this->assertInstanceOf('Cake\ORM\Association\BelongsToMany', $this->Tags->Articles);
    }

    /**
     * Test association with Articles
     */
    public function testArticlesAssociation(): void
    {
        // 既存のタグを取得
        $tag = $this->Tags->get(1, contain: ['Articles']);

        // 関連する記事が取得できることを確認
        $this->assertNotEmpty($tag->articles);
        $this->assertInstanceOf('App\Model\Entity\Article', $tag->articles[0]);

        // 新しいタグと記事の関連付け
        $newTag = $this->Tags->newEmptyEntity();
        $data = [
            'title' => 'Association Test Tag',
            'articles' => [
                '_ids' => [1], // 記事ID 1との関連付け
            ],
        ];
        $newTag = $this->Tags->patchEntity($newTag, $data);
        $result = $this->Tags->save($newTag);

        $this->assertNotFalse($result);

        // 保存後の関連を確認
        $savedTag = $this->Tags->get($newTag->id, contain: ['Articles']);
        $this->assertNotEmpty($savedTag->articles);
    }
}
