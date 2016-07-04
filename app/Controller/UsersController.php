<?php
    // app/Controller/UsersController.php
    App::uses('CakeEmail', 'Network/Email');
    App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $uses = array(
        'User', 
    );
    
    public $profileUploadDir = 'img/profiles';
    
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
        $this->Auth->allow('login');
    }

    public function login() {
        if($this->Session->check('Auth.User')){
            $this->redirect(array('controller'=>'dashboard', 'action' => 'index'));      
        }
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
                'contain'=>array(),
                
            ));
            
            if(!empty($checkuser)){
                $this->request->data['User']['id'] = $checkuser['User']['id'];
                $this->request->data['User']['password_old'] = null;
                $this->User->save($this->request->data);
            }
            
            if ($this->Auth->login()) {
                #set session info
                #check if user account is active
                $user = $this->Auth->user();
                #check user is active
                if($user['is_active'] == 0){
                    #set flash and redirect back to home page
                    $this->Session->destroy();
                    #$this->Cookie->destroy();
                    $this->Flash->alertBox('Your Account is Inactive', array(
                        'params' => array(
                            'class'=>'alert-danger'
                        )
                    ));
                    
                    $this->redirect(array('controller'=>'users', 'action' => 'logout'));
                }
                
                #Successfull Login
                $this->Flash->alertBox(
                    'Welcome, '. $this->Auth->user('first_name').' '. $this->Auth->user('last_name'), 
                    array(
                        'params' => array(
                            'class'=>'alert-success'
                        )
                    )
                );
                return $this->redirect(array('controller'=>'dashboard', 'action' => 'index'));
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
        $this->Session->destroy();
        return $this->redirect($this->Auth->logout());
    }

    public function index($letter = null, $status = null, $viewBy = null) {
        
        $letters = range('A', 'Z');
        array_unshift($letters, "All");
        $this->set('letters', $letters);
            
        $currentLetter = (is_null($letter)) ? 'All' : $letter ;
        $this->set('currentLetter', $currentLetter);
        $this->set('activeLetters', $letters);
        
        $deptClass= null;
        $accountClass= null;
        $roleClass= null;
        
        $this->set('viewBy', $viewBy);
        
        $options = array();
        
        switch($viewBy){
            case 'account':
                $option = array('order'=>array('Account.name' => 'asc'));
                $options = array_merge_recursive($options,$option);
                break;
                
            case 'role':
                $option = array('order'=>array('Role.lft' => 'asc'));
                $options = array_merge_recursive($options,$option);
                break;
                
            case 'department':
                $option = array('order'=>array('Department.name' => 'asc'));
                $options = array_merge_recursive($options,$option);
                break;
                
            default:
                $option = array('order'=>array('User.first_name' => 'asc', 'User.last_name' => 'asc'));
                $options = array_merge_recursive($options,$option);
                break;
        }
        
        $this->Paginator->settings = array(
            'conditions' => array(     
                #'Account.id' => $search_ids,
            ),
            'contain'=>array(
                'Account'=>array(),
                'Role'=>array(
                    'fields'=>array('Role.name', 'Role.lft')
                ),
                'Department'=>array(
                    'fields'=>array('Department.name', 'Department.abr')
                ),
                'Status'=>array(
                    'fields'=>array('Status.name', 'Status.color', 'Status.icon')
                ),
            ),
            'limit' => 100,
            #'order'=>array('User.first_name'=> 'asc', 'User.last_name'=> 'asc'),
            'fields'=>array(
                'User.id',
                'User.first_name',
                'User.last_name',
                'User.username',
                'User.email',
            ),
        );
        
        if($this->Auth->user('Role.permission_level') == 50){
            $option = array('conditions'=>array('Account.regional_admin_id' => $this->Auth->user('id')));
            
            $options = array_merge_recursive($options,$option);
        }
        
        if($this->Auth->user('Role.permission_level') <= 40){
            $option = array('conditions'=>array('User.account_id' => $this->Auth->user('account_id'), 'Role.lft >' => $this->Auth->user('Role.lft'), 'Role.rght <' => $this->Auth->user('Role.rght')));
            $options = array_merge_recursive($options,$option);
        }
         
        if(!is_null($letter) && $letter != 'All'){
            $option = array('conditions'=>array('User.first_name LIKE' => $letter.'%'));
            $options = array_merge_recursive($options,$option);
        }
        
        if(is_null($status) || $status == 'All'){
            $option = array('conditions'=>array('User.is_active' => array(1,2)));
            $options = array_merge_recursive($options,$option);
            $this->set('status', 'All');
        }else{
            $option = array('conditions'=>array('User.is_active' => $status));
            $options = array_merge_recursive($options,$option);
            $this->set('status', $status);
        }
        
        $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);
        #pr($this->Paginator->settings);
        #exit;
        $users = $this->Paginator->paginate('User');
        $result = array();
        
        foreach($users as $item){
            switch($viewBy){
                case 'account':
                    if(array_key_exists('name', $item['Account'])){
                        $indexName = $item['Account']['name'].' ( '. $item['Account']['abr'] .' )';
                        $keysort[$indexName] = $item['Account']['name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    $accountClass = 'active';
                    break;
                
                case 'role':
                    $indexName = $item['Role']['name'];
                    $keysort[$indexName] = $item['Role']['lft'];
                    $roleClass = 'active';
                    break;
                
                case 'department':
                    if(array_key_exists('name', $item['Department'])){
                        $indexName = $item['Department']['name'].' ( '. $item['Department']['abr'] .' )';
                        $keysort[$indexName] = $item['Department']['name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    $deptClass = 'active';
                    break;
                
                default:
                    $indexName = $item['User']['first_name'][0];
                    $keysort[$indexName] = $item['User']['first_name'][0];
                    break;
                
            }
            
            $value[$indexName][] = $item;
            
            $result = array_merge($result,$value);
        }
        
        if(!empty($result)){
            array_multisort($keysort, SORT_ASC, $result);
        }
        
        $this->set('users', $result);
        
        //set all active classes
        $this->set('accountClass', $accountClass);
        $this->set('deptClass', $deptClass);
        $this->set('roleClass', $roleClass);
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
            /*if ($this->User->save($this->request->data)) {
                $this->Flash->success(__('The user has been saved'));
                return $this->redirect(array('action' => 'index'));
            }*/
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