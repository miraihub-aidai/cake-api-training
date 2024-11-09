<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;

/**
 * ArticlesController
 *
 * 記事の管理を行うコントローラークラス
 *
 * @property \App\Model\Table\ArticlesTable $Articles
 * @property \App\Model\Table\TagsTable $Tags
 * @property \App\Model\Table\UsersTable $Users
 * @package App\Controller
 */
class ArticlesController extends AppController
{
    /**
     * 初期化メソッド
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
    }

    /**
     * beforeFilter メソッド
     *
     * コントローラーのアクションが実行される前に呼び出されるメソッドです。
     * 認証および認可の設定を行います。
     *
     * @param \Cake\Event\EventInterface $event イベントオブジェクト
     * @return void
     */
    public function beforeFilter(EventInterface $event): void
    {
        parent::beforeFilter($event);
    }

    /**
     * 記事一覧を表示するアクション
     *
     * @return void
     */
    public function index()
    {
        $articles = $this->paginate($this->Articles);
        $this->set(compact('articles'));
    }

    /**
     * 特定の記事を表示するアクション
     *
     * @param string|null $slug 記事のスラグ
     * @return void
     * @throws \Cake\Datasource\Exception\RecordNotFoundException 記事が見つからない場合
     */
    public function view(?string $slug = null)
    {
        // Update retrieving tags with contain()
        /** @var \Cake\ORM\Query $query */
        $query = $this->Articles->findBySlug($slug);
        $article = $query->contain('Tags')->firstOrFail();

        $this->set(compact('article'));
    }

    /**
     * 新しい記事を追加するアクション
     *
     * @return \Cake\Http\Response|null|void リダイレクト先
     */
    public function add()
    {
        $article = $this->Articles->newEmptyEntity();

        if ($this->request->is('post')) {
            $article = $this->Articles->patchEntity($article, $this->request->getData());

            /** @var \App\Model\Entity\Article $article */
            $article->user_id = 1;

            if ($this->Articles->save($article)) {
                $this->Flash->success('Your article has been saved.');

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Unable to add your article.');
        }

        // タグのリストを取得
        $tags = $this->Articles->Tags->find('list')->all();

        // ビューコンテキストに tags をセット
        $this->set('tags', $tags);
        $this->set('article', $article);
    }

    /**
     * 既存の記事を編集するアクション
     *
     * @param string $slug 編集する記事のスラグ
     * @return \Cake\Http\Response|null|void リダイレクト先
     * @throws \Cake\Datasource\Exception\RecordNotFoundException 記事が見つからない場合
     */
    public function edit(string $slug)
    {
        /** @var \Cake\ORM\Query $query */
        $query = $this->Articles->findBySlug($slug);
        $article = $query->contain('Tags')->firstOrFail();

        if ($this->request->is(['post', 'put'])) {
            $this->Articles->patchEntity($article, $this->request->getData(), [
                // 追加: user_id の更新を無効化
                'accessibleFields' => ['user_id' => false],
            ]);
            if ($this->Articles->save($article)) {
                $this->Flash->success('Your article has been updated.');

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('Unable to update your article.');
        }

        // タグのリストを取得
        $tags = $this->Articles->Tags->find('list')->all();

        // ビューコンテキストに tags をセット
        $this->set('tags', $tags);
        $this->set('article', $article);
    }

    /**
     * 指定されたスラグの記事を削除するアクション
     *
     * @param string $slug 削除する記事のスラグ
     * @return \Cake\Http\Response|null 削除成功時はインデックスページへリダイレクト
     * @throws \Cake\Datasource\Exception\RecordNotFoundException 記事が見つからない場合
     * @throws \Cake\Http\Exception\MethodNotAllowedException 許可されていないHTTPメソッドでアクセスした場合
     */
    public function delete(string $slug)
    {
        $this->request->allowMethod(['post', 'delete']);

        // $article = $this->Articles->findBySlug($slug)->firstOrFail();
        /** @var \Cake\ORM\Query $query */
        $query = $this->Articles->findBySlug($slug);
        $article = $query->contain('Tags')->firstOrFail();

        if ($this->Articles->delete($article)) {
            $this->Flash->success('The {0} article has been deleted.', $article->title);

            return $this->redirect(['action' => 'index']);
        }

        return null;
    }

    /**
     * 指定されたタグを持つ記事を表示するアクション
     *
     * @return void
     */
    public function tags()
    {
        // 'pass' キーは CakePHP によって提供され、リクエストに渡された
        // 全ての URL パスセグメントを含みます。
        $tags = $this->request->getParam('pass');

        // ArticlesTable を使用してタグ付きの記事を検索します。
        $articles = $this->Articles->find('tagged', [
            'tags' => $tags,
        ])
        ->all();

        // 変数をビューテンプレートのコンテキストに渡します。
        $this->set([
            'articles' => $articles,
            'tags' => $tags,
        ]);
    }
}
