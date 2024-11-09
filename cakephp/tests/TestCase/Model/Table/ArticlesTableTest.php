<?php
declare(strict_types=1);

namespace App\Test\TestCase\Model\Table;

use App\Model\Table\ArticlesTable;
use Cake\TestSuite\TestCase;

class ArticlesTableTest extends TestCase
{
    /**
     * @var ArticlesTable $Articles ArticlesTable
     */
    protected ArticlesTable $Articles;

    /**
     * @var array $fixtures fixtures
     */
    protected array $fixtures = [
        'app.Users',
        'app.Articles',
        'app.Tags',
        'app.ArticlesTags',
    ];

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->Articles = $this->fetchTable('Articles');
    }

    /** tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Articles);
        parent::tearDown();
    }

    /**
     * Test validationDefault method
     *
     * @return void
     */
    public function testValidationDefault(): void
    {
        // 正常系テスト
        $data = [
            'title' => 'Valid Title 10chars',
            'body' => 'Valid body content with more than 10 characters',
            'user_id' => 1,
        ];
        $article = $this->Articles->newEntity($data);
        $this->assertEmpty($article->getErrors());

        // タイトル必須チェック
        $invalidData = [
            'body' => 'Valid body content',
            'user_id' => 1,
        ];
        $article = $this->Articles->newEntity($invalidData);
        $this->assertNotEmpty($article->getErrors());
        $this->assertArrayHasKey('title', $article->getErrors());
        $this->assertSame(
            '記事のタイトルは必須です',
            $article->getErrors()['title']['_required']
        );

        // タイトル最小長チェック
        $shortTitleData = [
            'title' => 'Short',
            'body' => 'Valid body content',
            'user_id' => 1,
        ];
        $article = $this->Articles->newEntity($shortTitleData);
        $this->assertNotEmpty($article->getErrors());
        $this->assertArrayHasKey('title', $article->getErrors());
        $this->assertSame(
            'タイトルは最低10文字必要です',
            $article->getErrors()['title']['minLength']
        );
    }

    /**
     * Test beforeSave method
     *
     * @return void
     */
    public function testBeforeSave(): void
    {
        $data = [
            'title' => 'New Article Title',
            'body' => 'New Article Body with more than 10 chars',
            'user_id' => 1,
            'tag_string' => 'Tag1, Tag2, Tag3',
        ];

        $article = $this->Articles->newEntity($data);
        $this->Articles->save($article);

        $this->assertSame(
            'New-Article-Title',
            $article->slug,
            'Slug should be generated from title'
        );

        $this->assertCount(3, $article->tags, 'Should have exactly 3 tags');

        $tagTitles = collection($article->tags)
            ->extract('title')
            ->toArray();
        $this->assertSame(['Tag1', 'Tag2', 'Tag3'], $tagTitles);
    }

    /**
     * Test findTagged method
     *
     * @return void
     */
    public function testFindTagged(): void
    {
        // タグなしの記事を探すテスト
        $noTagArticles = $this->Articles->find('tagged', tags: [])
            ->contain('Tags')
            ->all();

        $this->assertIsIterable($noTagArticles);

        // PHPタグを持つ記事を検索
        $taggedArticles = $this->Articles->find('tagged', tags: ['PHP'])
            ->contain('Tags')
            ->all()
            ->toArray();

        $this->assertNotEmpty($taggedArticles, 'Should find articles tagged with PHP');

        $article = $taggedArticles[0];
        $this->assertNotEmpty($article->tags, 'Article should have tags');

        // タグ名を抽出
        $tagTitles = collection($article->tags)
            ->extract('title')
            ->toArray();

        $this->assertContains(
            'PHP',
            $tagTitles,
            'Article should be tagged with PHP'
        );
    }

    /**
     * Test buildTags method
     *
     * @return void
     */
    public function testBuildTags(): void
    {
        $data = [
            'title' => 'Test Article With Tags',
            'body' => 'Test Body Content with sufficient length',
            'user_id' => 1,
            'tag_string' => 'PHP, New Tag, Another New',
        ];

        $article = $this->Articles->newEntity($data);
        $this->Articles->save($article);

        $savedArticle = $this->Articles->get(
            $article->id,
            contain: ['Tags']
        );

        $this->assertCount(3, $savedArticle->tags, 'Should have exactly 3 tags');

        $tagTitles = collection($savedArticle->tags)
            ->extract('title')
            ->toList();

        $expectedTags = ['PHP', 'New Tag', 'Another New'];
        foreach ($expectedTags as $tagTitle) {
            $this->assertContains(
                $tagTitle,
                $tagTitles,
                sprintf('Tag "%s" should be saved', $tagTitle)
            );
        }

        $phpTag = collection($savedArticle->tags)
            ->firstMatch(['title' => 'PHP']);
        $this->assertNotNull($phpTag, 'Existing PHP tag should be reused');
    }
}
