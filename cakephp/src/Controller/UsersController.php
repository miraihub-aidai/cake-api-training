<?php
declare(strict_types=1);

namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Http\Response;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 */
class UsersController extends AppController
{
    /**
     * コンポーネントのロード
     *
     * 認証およびフラッシュメッセージのコンポーネントをロードします。
     *
     * @var array<string>
     */
    protected array $components = ['Authentication', 'Flash'];

    /**
     * コントローラーのアクションが実行される前に呼び出されるメソッド
     *
     * @param \Cake\Event\EventInterface $event イベントオブジェクト
     * @return void
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        // 認証を必要としないログインアクションを構成し、
        // 無限リダイレクトループの問題を防ぎます
        // $this->Authentication->addUnauthenticatedActions(['login']);
        $this->Authentication->addUnauthenticatedActions(['login', 'add', 'logout']);
    }

    /**
     * ユーザーログイン処理を行うアクション
     *
     * @return \Cake\Http\Response|null ログイン成功時はリダイレクト、それ以外の場合はnull
     */
    public function login(): ?Response
    {
        $this->request->allowMethod(['get', 'post']);
        $result = $this->Authentication->getResult();

        // POST, GET を問わず、ユーザーがログインしている場合はリダイレクトします
        if ($result && $result->isValid()) {
            // redirect to /articles after login success
            $redirect = $this->request->getQuery('redirect', [
                'controller' => 'Articles',
                'action' => 'index',
            ]);

            return $this->redirect($redirect);
        }

        // ユーザーが submit 後、認証失敗した場合は、エラーを表示します
        if ($this->request->is('post')) {
            if ($result && $result->isValid()) {
                $target = $this->Authentication->getLoginRedirect() ?? '/users/login';

                return $this->redirect($target);
            }
            $this->Flash->error('Invalid username or password');
        }

        return null;
    }

    /**
     * ユーザーのログアウト処理を行うアクション
     *
     * このメソッドは、ユーザーをログアウトさせ、ログインページにリダイレクトします。
     * ユーザーがログインしていない場合は、何も行いません。
     *
     * @return \Cake\Http\Response|null ログアウト成功時はリダイレクト、それ以外の場合はnull
     */
    public function logout(): ?Response
    {
        $result = $this->Authentication->getResult();
        // POST, GET を問わず、ユーザーがログインしている場合はリダイレクトします
        if ($result && $result->isValid()) {
            $this->Authentication->logout();

            return $this->redirect(['controller' => 'Users', 'action' => 'login']);
        }

        return null;
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $query = $this->Users->find();
        $users = $this->paginate($query);

        $this->set(compact('users'));
    }

    /**
     * View method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view(?string $id = null)
    {
        $user = $this->Users->get($id, contain: ['Articles']);
        $this->set(compact('user'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $user = $this->Users->newEmptyEntity();
        if ($this->request->is('post')) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success('The user has been saved.');

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('The user could not be saved. Please, try again.');
        }
        $this->set(compact('user'));
    }

    /**
     * Edit method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit(?string $id = null)
    {
        $user = $this->Users->get($id, contain: []);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $user = $this->Users->patchEntity($user, $this->request->getData());
            if ($this->Users->save($user)) {
                $this->Flash->success('The user has been saved.');

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error('The user could not be saved. Please, try again.');
        }
        $this->set(compact('user'));
    }

    /**
     * Delete method
     *
     * @param string|null $id User id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete(?string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $user = $this->Users->get($id);
        if ($this->Users->delete($user)) {
            $this->Flash->success('The user has been deleted.');
        } else {
            $this->Flash->error('The user could not be deleted. Please, try again.');
        }

        return $this->redirect(['action' => 'index']);
    }
}
