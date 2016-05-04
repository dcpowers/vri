<?php
    // app/Controller/UsersController.php
    App::uses('CakeEmail', 'Network/Email');
    App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $uses = array(
        'User', 
    );
    
    public $helpers = array('Session');
    
    public $components = array('RequestHandler', 'Paginator');
    
    public function pluginSetup() {
        $user = AuthComponent::user();
        $role_names = Set::extract( AuthComponent::user(), '/AuthRole/name' );
        $link = array();
        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.SiteName', 'Employees');
    }

    public function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow('add', 'logout');
    }

    public function login() {
        if ($this->request->is('post')) {
            /**
            *   Need to update password. this is for users coming into the new system the first time.
            *   will check for this in the DB.
            */
            
            $checkuser = $this->User->find('first', array(
                'conditions'=>array(
                    'User.username' => $this->request->data['User']['username'],
                    'User.is_active' => 1, 
                    'User.password_old' => md5($this->request->data['User']['password'])
                ),
                
            ));
            
            if(!empty($checkuser)){
                $this->request->data['User']['id'] = $checkuser['User']['id'];
                $this->request->data['User']['password_old'] = null;
                $this->User->save($this->request->data);
            }
            
            if ($this->Auth->login()) {
                return $this->redirect($this->Auth->redirectUrl());
            }
            
            $this->Flash->alertBox('Invalid username or password, try again', array(
                'params' => array(
                    'class'=>'alert-danger'
                )
            ));
            
        }
        
        $this->layout = 'login';
    }

    public function logout() {
        return $this->redirect($this->Auth->logout());
    }

    public function index() {
        $this->User->recursive = 0;
        $this->set('users', $this->paginate());
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        $this->set('user', $this->User->findById($id));
    }

    public function add() {
        if ($this->request->is('post')) {
            $this->User->create();
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        }
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->request->is('post') || $this->request->is('put')) {
            if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );
        } else {
            $this->request->data = $this->User->findById($id);
            unset($this->request->data['User']['password']);
        }
    }

    public function delete($id = null) {
        // Prior to 2.5 use
        // $this->request->onlyAllow('post');

        $this->request->allowMethod('post');

        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }
        if ($this->User->delete()) {
            $this->Flash->success(__('User deleted'));
            return $this->redirect(array('action' => 'index'));
        }
        $this->Flash->error(__('User was not deleted'));
        return $this->redirect(array('action' => 'index'));
    }

}