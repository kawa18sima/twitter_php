<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Event\Event;

/**
 * Users Controller
 *
 * @property \App\Model\Table\UsersTable $Users
 *
 * @method \App\Model\Entity\User[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->set('user', $this->Auth->user());
        $this->Auth->allow();
    }

    public function view($id = null){
        $this->loadModel('Messages');
        $this->loadModel('Following');
        $this->paginate = [
            'limit' => 10,
            'order' => [
                'Messages.id' => 'desc'
            ],
            'contain' => ['Users']
        ];
        $show_user = $this->Users->find()->where(['id = ' => $id])->first();
        $messages = $this->paginate($this->Messages->find()->where(['user_id = ' => $id]));
        $message_count = $this->Messages->countMessage($id);
        $follow = $this->Following->followUsers($id);
        $follower = $this->Following->followerUsers($id);
        $this->set(compact('show_user', 'messages', 'message_count' ,'follow', 'follower'));
     }

    public function signup()
    {
        $user = $this->Auth->user();
        if(isset($user)){
            $this->redirect(['controller'=>'Users','action'=>'index']);
        }
        else{
            $user = $this->Users->newEntity();
            if ($this->request->is('post')){
                $user = $this->Users->patchEntity($user, $this->request->getData());
                if ($this->Users->save($user)){
                    $this->set('user', $user);
                    $this->render('complete');
                }
            }
            $this->set(compact('user'));
        }
    }

    public function login(){
        $user = $this->Auth->user();
        if(isset($user)){
            $this->redirect(['controller'=>'Users','action'=>'index']);
        }
        else{
            if ($this->request->is('post')){
                $user = $this->Auth->identify();
                if ($user) {
                    $this->Auth->setUser($user);
                    return $this->redirect($this->Auth->redirectUrl());
                }
                $this->Flash->error(__('ユーザ名もしくはパスワードが間違っています'));
            }

        }
    }

    public function logout(){
        $this->Flash->success('ログアウトしました');
        return $this->redirect($this->Auth->logout());
    }
    public function find(){

    }

    public function result($find = null){
        $this->loadModel('Messages');
        $this->loadModel('Following');
        if($this->request->is('post')){
            $find = $this->request->data['find'];
            $this->redirect(['action' => 'result', $find]);
        }
        else{
            $this->paginate = [
                'limit' => 10,
                'order' => [
                    'Users.id' => 'desc'
                ],
                'contain' => ['Messages']
            ];
            $results = $this->paginate($this->Users->find('all')->where(["name like " => '%'. $find . '%'])->orWhere(["username like " => '%' . $find . '%']));
            $follower = $this->Following->followUsers($this->Auth->user()['id']);
            $this->set(compact('find', 'results', 'follower'));
        }
    }

    public function follow($id = null){
        $this->loadModel('Messages');
        $this->loadModel('Following');

        $this->paginate = [
            'limit' => 10,
            'order' => [
                'Users.id' => 'desc'
            ],
            'contain' => ['Users']
        ];

        
        $show_user = $this->Users->find()->where(['id = ' => $id])->first();
        $message_count = $this->Messages->countMessage($id);
        $follow = $this->Following->followUsers($id);
        $follower = $this->Following->followerUsers($id);
        $login_user_follow = $this->Following->followUsers($this->Auth->user()['id']);

        $users = $this->paginate($this->Following->find('all')->where(["user_id " => $id]));
        $this->set(compact('users', 'show_user', 'message_count' ,'follow', 'follower', 'login_user_follow'));

    }

    public function follower($id = null){
        $this->loadModel('Messages');
        $this->loadModel('Following');

        $this->paginate = [
            'limit' => 10,
            'order' => [
                'Users.id' => 'desc'
            ],
            'contain' => ['Users']
        ];

        $users = $this->paginate($this->Following->find()->where(['follower_id = ' => $id]));
        $show_user = $this->Users->find()->where(['id = ' => $id])->first();
        $messages = $this->Messages->find()->where(['user_id = ' => $id]);
        $message_count = $this->Messages->countMessage($id);
        $follow = $this->Following->followUsers($id);
        $follower = $this->Following->followerUsers($id);
        $login_user_follow = $this->Following->followUsers($this->Auth->user()['id']);
        $this->set(compact('users', 'show_user', 'message_count' ,'follow', 'follower', 'login_user_follow'));
    }


}
