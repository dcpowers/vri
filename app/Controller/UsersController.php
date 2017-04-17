<?php

    // app/Controller/UsersController.php
    App::uses('CakeEmail', 'Network/Email');
    App::uses('AppController', 'Controller');

class UsersController extends AppController {
    public $uses = array(
        'User',
        'AuthRole',
        'AccountUser',
        'AccountDepartment',
        'DepartmentUser',
        'TrainingMembership',
        'TrainingRecord',
        'Account',
        'Department'
    );

    public $profileUploadDir = 'img/profiles';

    public function isAuthorized($user = null) {
        return true;
    }

    #public $helpers = array('Session');

    public $components = array('Search.Prg', 'RequestHandler', 'Paginator');

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
            $this->redirect(array('controller'=>'Dashboard', 'action' => 'index'));
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

            #pr($checkuser);

            if(!empty($checkuser)){

                $this->request->data['User']['id'] = $checkuser['User']['id'];
                $this->request->data['User']['password_old'] = null;

                $this->User->save($this->request->data);
            }

            if ($this->Auth->login()) {
                #set session info
                #check if user account is active
                $user = $this->User->find('first', array(
                    'conditions'=>array(
                        'User.id' => $this->Auth->user('id')
                    ),
                    'contain'=>array(
                        'DepartmentUser',
                        'AccountUser',
                    )
                ));
                unset($user['User']);
                $user = array_merge($this->Auth->user(), $user);

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

                $this->Session->write('Auth.User', $user);

                #Successfull Login
                $this->Flash->alertBox(
                    'Welcome, '. $this->Auth->user('first_name').' '. $this->Auth->user('last_name'),
                    array(
                        'params' => array(
                            'class'=>'alert-success'
                        )
                    )
                );

                #pr('--');
                #pr($user);
                #pr('here');
                #exit;
                return $this->redirect(array('controller'=>'Dashboard', 'action' => 'index'));
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
        #$this->Session->destroy();
        return $this->redirect($this->Auth->logout());
    }

    public function index($letter = null, $status = null, $viewBy = null) {
        $letters = range('A', 'Z');
        array_unshift($letters, "All");
        $this->set('letters', $letters);

        $currentLetter = (is_null($letter)) ? 'All' : $letter ;
        $this->set('currentLetter', $currentLetter);

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
                $option = array('order'=>array('DepartmentUser.Department.name' => 'asc'));
                $options = array_merge_recursive($options,$option);
                break;

            default:
                $option = array('order'=>array('User.first_name' => 'asc', 'User.last_name' => 'asc'));
                $options = array_merge_recursive($options,$option);
                break;
        }

        $this->Paginator->settings = array(
            'conditions' => array(
                #'Account.id !=' => ,
            ),
            'contain'=>array(
                'Role'=>array(
                    'fields'=>array('Role.name', 'Role.lft')
                ),
                'DepartmentUser'=>array(
                    'Department'=>array(
                        'fields'=>array('Department.name', 'Department.abr')
                    )
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

		$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
		$dept_ids = Set::extract( AuthComponent::user(), '/DepartmentUser/department_id' );

		#pr($account_ids);
		#exit;
		if(AuthComponent::user('Role.permission_level') >= 30){
            $user_ids = $this->AccountUser->getAccountIds($account_ids);
			$option = array('conditions'=>array('User.id' => $user_ids));
        	$options = array_merge_recursive($options,$option);
        }

		if(AuthComponent::user('Role.permission_level') == 20){
            $user_ids = $this->DepartmentUser->getUserIds($dept_ids);
			$option = array('conditions'=>array('User.id' => $user_ids));
        	$options = array_merge_recursive($options,$option);
        }

		if(AuthComponent::user('Role.permission_level') == 10){
            $option = array('conditions'=>array('User.supervisor_id' => AuthComponent::user('id')));
        	$options = array_merge_recursive($options,$option);
        }

		if(!is_null($letter) && $letter != 'All'){
            $option = array('conditions'=>array('User.first_name LIKE' => $letter.'%'));
            $options = array_merge_recursive($options,$option);
        }

        if(!empty($this->request->data['Search']['q'])){
            $option = array('conditions'=>array('OR'=>array('User.first_name LIKE' => '%'.$this->request->data['Search']['q'].'%', 'User.last_name LIKE' => '%'.$this->request->data['Search']['q'].'%' )));
            $options = array_merge_recursive($options,$option);
        }

        if(is_null($status)){
            $status = 1;
        }

		if($status == 'All'){
            $option = array('conditions'=>array('User.is_active' => array(1,2)));
            $options = array_merge_recursive($options,$option);
            $this->set('status', 'All');
        }else{
            $option = array('conditions'=>array('User.is_active' => $status));
            $options = array_merge_recursive($options,$option);
            $this->set('status', $status);
        }


		$this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);
        $users = $this->Paginator->paginate('User');
        #pr($this->Paginator->settings);
        #pr($users);
        #exit;
        $result = array();

        $accountClass = null;
        $deptClass = null;
        $roleClass = null;
        $title = null;
        $letter = array();

        foreach($users as $item){
            switch($viewBy){
                case 'account':
                    if(!empty($item['AccountUser'])){
                        foreach($item['AccountUser'] as $newItem){
                            if(array_key_exists('name', $newItem['Account'])){
                                $indexName = $newItem['Account']['name'].' ( '. $newItem['Account']['abr'] .' )';
                                $keysort[$indexName] = $newItem['Account']['name'];
                            }else{
                                $indexName = '--';
                                $keysort[$indexName] = '--';
                            }

                            #pr($newItem);
                            #exit;
                            $value[$indexName][] = $item;
                        }
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';

                        $value[$indexName][] = $item;
                    }
                    $accountClass = 'active';
                    $title = '<small>By Account</small>';

                    $value[$indexName][] = $item;

                    break;

                case 'role':
                    $indexName = $item['Role']['name'];
                    $keysort[$indexName] = $item['Role']['lft'];
                    $roleClass = 'active';
                    $title = '<small>By User Role</small>';

                    $value[$indexName][] = $item;

                    break;

                case 'department':
                    if(!empty($item['DepartmentUser'])){
                        foreach($item['DepartmentUser'] as $newItem){
                            $indexName = $newItem['Department']['name'].' ( '. $newItem['Department']['abr'] .' )';
                            $keysort[$indexName] = $newItem['Department']['name'];

                            $value[$indexName][] = $item;
                        }

                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';

                        $value[$indexName][] = $item;
                    }
                    $deptClass = 'active';
                    $title = '<small>By Department</small>';
                    break;

                default:
                    $indexName = $item['User']['first_name'][0];
                    $keysort[$indexName] = $item['User']['first_name'][0];
                    $value[$indexName][] = $item;
                    break;
            }
			$letter[] = $item['User']['first_name'][0];

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
        $this->set('title', $title);
		$this->set('activeLetters', $letter);
    }

    public function viewBak($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        $user = $this->request->data = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $id,
            ),
            'contain'=>array(
                'AccountUser'=>array(
                    'Account'=>array(
                        'fields'=>array(
                            'Account.id',
                            'Account.name'
                        )
                    )
                ),
                'Role'=>array(
                    'fields'=>array('Role.name', 'Role.lft')
                ),
                'Asset'=>array(
                    'Manufacturer'=>array(
                        'fields'=>array(
                            'Manufacturer.id',
                            'Manufacturer.name'
                        )
                    ),
                    'fields'=>array(
                        'Asset.id',
                        'Asset.asset',
                        'Asset.tag_number',
                        'Asset.model',
                    )
                ),
                'Supervisor'=>array(
                    'fields'=>array('Supervisor.first_name', 'Supervisor.last_name')
                ),
                'DepartmentUser'=>array(
                    'Department'=>array(
                        'fields'=>array('Department.id','Department.name', 'Department.abr')
                    )
                ),
                'Status'=>array(
                    'fields'=>array('Status.name', 'Status.color', 'Status.icon')
                ),
                'TrainingRecord'=>array(
                    'Training'=>array(
                        'fields'=>array(
                            'Training.name'
                        )
                    )
                ),
                'TrainingExempt'=>array()
            ),
        ));
        $user['records'] = array();

        foreach($user['TrainingRecord'] as $data){
            if(!empty($data['Training']['name'])){
                $index = $data['Training']['name'];
            }else{
                $index = 'Untitled';
            }
            unset($data['Training']);
            $user['records'][$index][] = $data;
        }
        unset($user['TrainingRecord']);

        $account_ids = Hash::extract($user, 'AccountUser.{n}.account_id');
        $department_ids = Hash::extract($user, 'DepartmentUser.{n}.department_id');

        $requiredTraining = $this->TrainingMembership->getRequiredTraining($account_ids,$department_ids, $id);
        $records = $this->TrainingRecord->findRecords($requiredTraining, $id);

        if ($this->request->is('requested')) {
            return array($requiredTraining, $records);
        }
        $this->set('user', $user);
        $this->set('records', $records);
        $this->set('requiredTraining', $requiredTraining);
    }

    public function add($account_id=null) {
        if ($this->request->is('post')) {
            $error = false;

            $this->User->set($this->request->data);
            if(!$this->User->validates()){
                $validationErrors['User'] = $this->User->validationErrors;
                $error = true;
            }

            if($error == false){
                $this->User->create();
                /*if ($this->User->save($this->request->data)) {
                    $this->Flash->success(__('The user has been saved'));
                    return $this->redirect(array('action' => 'index'));
                }*/
            }
            $this->Flash->error(
                __('The user could not be saved. Please, try again.')
            );

            $this->set( compact( 'validationErrors' ) );
        }

        $this->set('account', $this->Account->pickListById($account_id));
        $this->set('status', $this->User->statusInt());
        $this->set('pickListByAccount', $this->AccountUser->pickList($account_id));
        $this->set('accounts', $this->Account->pickListActive());
        $this->set('empStatus', $this->Account->empPayStatus());
        $this->set('departments', $this->Department->pickList());
        $this->set('roles', $this->AuthRole->pickListByRole($this->Auth->user('Role.id')));


    }

	public function member_getUserInfo(){

        $record = $this->User->getUserInfo();

        return $record;
    }

    public function edit($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if(!empty($this->request->data['User']['doh'])){
                $this->request->data['User']['doh'] = date('Y-m-d', strtotime($this->request->data['User']['doh']));
            }

            if(!empty($this->request->data['User']['dob'])){
                $this->request->data['User']['dob'] = date('Y-m-d', strtotime($this->request->data['User']['dob']));
            }

            $c = 0;
            $this->AccountUser->deleteAll(array('AccountUser.user_id' => $this->request->data['User']['id']), false);
            if(!empty($this->request->data['AccountUser']['account_id'])){
                foreach ($this->request->data['AccountUser']['account_id'] as $account_id){
                    $this->request->data['AccountUser'][$c]['account_id'] = $account_id;
                    $this->request->data['AccountUser'][$c]['user_id'] = $this->request->data['User']['id'];
                    $c++;
                }

                unset($this->request->data['AccountUser']['account_id']);
            }

            $c = 0;
            $this->DepartmentUser->deleteAll(array('DepartmentUser.user_id' => $this->request->data['User']['id']), false);
            if(!empty($this->request->data['DepartmentUser']['department_id'])){
                foreach ($this->request->data['DepartmentUser']['department_id'] as $account_id){
                    $this->request->data['DepartmentUser'][$c]['department_id'] = $account_id;
                    $this->request->data['DepartmentUser'][$c]['user_id'] = $this->request->data['User']['id'];
                    $c++;
                }

                unset($this->request->data['DepartmentUser']['department_id']);
            }
            #pr($this->request->data);
            #pr($this->User->validationErrors);
            #exit;
            if($this->User->saveAll($this->request->data)) {
                 $this->Flash->alertBox(
                    'The user has been saved',
                    array(
                        'params' => array(
                            'class'=>'alert-success'
                        )
                    )
                );
                return $this->redirect(array('controller'=>'Users', 'action' => 'view', $this->request->data['User']['id']));
            }

            #debug($this->User->validationErrors); //show validationErrors
            #debug($this->User->getDataSource()->getLog(false, false)); //show last sql query
            #exit;

            $this->Flash->alertBox(
                'The user could not be saved. Please, try again.',
                array(
                    'params' => array(
                        'class'=>'alert-danger'
                    )
                )
            );

        }

        $this->request->data = $this->User->findById($id);
        unset($this->request->data['User']['password']);

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

        $this->set('status', $this->User->statusInt());
        $this->set('pickListByAccount', $this->AccountUser->pickList($account_ids));
        $this->set('accounts', $this->Account->pickListActive());
        $this->set('departments', $this->AccountDepartment->pickListByAccount($account_ids));
        $this->set('roles', $this->AuthRole->pickListByRole($this->Auth->user('Role.id')));

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

    public function resetPassword($id = null){
        $this->request->data['User']['id'] = $id;
        $this->request->data['User']['ForcePassChange'] = 1;
        $this->request->data['User']['password'] = 'vanguard';
        $this->request->data['User']['password_confirm'] = 'vanguard';
        #debug(get_class($this->User));
        #pr($this->request->data);
        #exit;
        $this->User->save($this->request->data);



    }

    public function updateSupervisorList($id = null){
        $data = $this->AccountUser->pickList($id);

        $this->set(compact('data'));
    }

    public function updateDeptList($id = null){
        $data = $this->Department->pickListById($id);

        $this->set(compact('data'));
    }

    public function userListByDept($id = null){
        $data = $this->DepartmentUser->pickListByDept($id);

        $this->set(compact('data'));
    }

    public function view($id = null) {
        $this->User->id = $id;
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {
            if(!empty($this->request->data['User']['doh'])){
                $this->request->data['User']['doh'] = date('Y-m-d', strtotime($this->request->data['User']['doh']));
            }

            if(!empty($this->request->data['User']['dob'])){
                $this->request->data['User']['dob'] = date('Y-m-d', strtotime($this->request->data['User']['dob']));
            }

            #pr($this->request->data);
            #exit;
            #pr($this->request->data);
            //update profile image if not empty
            if(!empty($this->request->data['User']['file'])) {
                $check = $this->User->uploadFile($this->request->data['User']['file']);

                unset($this->request->data['User']['file']);
            }


			$this->DepartmentUser->deleteAll(array('DepartmentUser.user_id' => $this->request->data['User']['id']), false);
            if(!empty($this->request->data['DepartmentUser']['department_id'])){
                $this->request->data['DepartmentUser'][0]['user_id'] = $this->request->data['User']['id'];
                $this->request->data['DepartmentUser'][0]['department_id'] = $this->request->data['DepartmentUser']['department_id'];

				unset($this->request->data['DepartmentUser']['department_id']);
            }

			$this->AccountUser->deleteAll(array('AccountUser.user_id' => $this->request->data['User']['id']), false);
            if(!empty($this->request->data['AccountUser']['account_id'])){
                $this->request->data['AccountUser'][0]['user_id'] = $this->request->data['User']['id'];
                $this->request->data['AccountUser'][0]['account_id'] = $this->request->data['AccountUser']['account_id'];

				unset($this->request->data['AccountUser']['account_id']);
            }
            #pr($check);
            #pr($this->request->data['User']['id']);
            #pr($this->request->data);
            #exit;
            if($this->User->saveAll($this->request->data)) {
                 $this->Flash->alertBox(
                    'User Infomation has been saved', [
                        'key' => 'profile',
                        'params' => [ 'class'=>'alert-success' ]
                    ]
                );

                #return $this->redirect(array('controller'=>'Users', 'action' => 'profile'));
            }else{
                #debug($this->User->validationErrors); //show validationErrors
                #debug($this->User->getDataSource()->getLog(false, false)); //show last sql query
                #exit;

                $this->Flash->alertBox(
                    'User Information could not be saved. Please, try again.', [
                        'key' => 'profile',
                        'params' => [ 'class'=>'alert-danger' ]
                    ]
                );

                #return $this->redirect(array('controller'=>'Users', 'action' => 'profile'));
            }

        }

        $user = $this->request->data = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $id
            ),
            'contain'=>array(
                'AccountUser'=>array(
                    'Account'=>array(
                        'fields'=>array(
                            'Account.id',
                            'Account.name'
                        )
                    )
                ),
                'Role'=>array(
                    'fields'=>array('Role.name', 'Role.lft')
                ),
                'Asset'=>array(
                    'Manufacturer'=>array(
                        'fields'=>array(
                            'Manufacturer.id',
                            'Manufacturer.name'
                        )
                    ),
                    'fields'=>array(
                        'Asset.id',
                        'Asset.asset',
                        'Asset.tag_number',
                        'Asset.model',
                    )
                ),
                'Supervisor'=>array(
                    'fields'=>array('Supervisor.first_name', 'Supervisor.last_name')
                ),
                'DepartmentUser'=>array(
                    'Department'=>array(
                        'fields'=>array('Department.id','Department.name', 'Department.abr')
                    )
                ),
                'Status'=>array(
                    'fields'=>array('Status.name', 'Status.color', 'Status.icon')
                ),
                'TrainingExempt'=>array(),
                'Accident'=>array(
					'AccidentArea'=>array(
						'AccidentAreaLov'=>array()
					)
				)
            ),

        ));

        $account_ids = Hash::extract($user, 'AccountUser.{n}.account_id');
        $department_ids = Hash::extract($user, 'DepartmentUser.{n}.department_id');

        $requiredTraining = $this->TrainingMembership->getRequiredTraining($account_ids,$department_ids,$id);
        $allTraining = $this->TrainingMembership->getAllTraining($account_ids,$department_ids,$id);

        $records = $this->TrainingRecord->findRecords($requiredTraining, $id);
        $allRecords = $this->TrainingRecord->findRecords($allTraining, $id);

        if ($this->request->is('requested')) {
            return array($requiredTraining, $records);
        }
        #pr($allTraining);
        #exit;
        $this->set('user', $user);
        $this->set('records', $records);
        $this->set('allRecords', $allRecords);
        $this->set('payStatus', $this->User->empPayStatus());
        $this->set('status', $this->User->statusInt());
        $this->set('yesNo', $this->User->yesNo());
        $this->set('pickListByAccount', $this->AccountUser->pickList($account_ids));
        $this->set('accounts', $this->Account->pickListActive());
        $this->set('departments', $this->AccountDepartment->pickListByAccount($account_ids));
        $this->set('roles', $this->AuthRole->pickListByRole($this->Auth->user('Role.id')));
		$this->set('empStatus', $this->Account->empPayStatus());
    }

    public function profile(){
        $this->User->id = AuthComponent::user('id');
        if (!$this->User->exists()) {
            throw new NotFoundException(__('Invalid user'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            if(!empty($this->request->data['User']['dob'])){
                $this->request->data['User']['dob'] = date('Y-m-d', strtotime($this->request->data['User']['dob']));
            }

            #pr($this->request->data);
            #exit;
            pr($this->request->data);
            //update profile image if not empty
            if(!empty($this->request->data['User']['file'])) {
                $check = $this->User->uploadFile($this->request->data['User']['file']);

            }
            #pr($check);
            unset($this->request->data['User']['file']);
            #pr($this->request->data);
            #exit;
            if($this->User->save($this->request->data)) {
                 $this->Flash->alertBox(
                    'Your profile has been saved', [
                        'key' => 'profile',
                        'params' => [ 'class'=>'alert-success' ]
                    ]
                );

                return $this->redirect(array('controller'=>'Users', 'action' => 'profile'));
            }

            #debug($this->User->validationErrors); //show validationErrors
            #debug($this->User->getDataSource()->getLog(false, false)); //show last sql query
            #exit;

            $this->Flash->alertBox(
                'Profile could not be saved. Please, try again.', [
                    'key' => 'profile',
                    'params' => [ 'class'=>'alert-danger' ]
                ]
            );

            return $this->redirect(array('controller'=>'Users', 'action' => 'profile'));

        }

        $user = $this->request->data = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => AuthComponent::user('id')
            ),
            'contain'=>array(
                'AccountUser'=>array(
                    'Account'=>array(
                        'fields'=>array(
                            'Account.id',
                            'Account.name'
                        )
                    )
                ),
                'Role'=>array(
                    'fields'=>array('Role.name', 'Role.lft')
                ),
                'Asset'=>array(
                    'Manufacturer'=>array(
                        'fields'=>array(
                            'Manufacturer.id',
                            'Manufacturer.name'
                        )
                    ),
                    'fields'=>array(
                        'Asset.id',
                        'Asset.asset',
                        'Asset.tag_number',
                        'Asset.model',
                    )
                ),
                'Supervisor'=>array(
                    'fields'=>array('Supervisor.first_name', 'Supervisor.last_name')
                ),
                'DepartmentUser'=>array(
                    'Department'=>array(
                        'fields'=>array('Department.id','Department.name', 'Department.abr')
                    )
                ),
                'Status'=>array(
                    'fields'=>array('Status.name', 'Status.color', 'Status.icon')
                ),
                'TrainingExempt'=>array()
            ),

        ));


        $account_ids = Hash::extract($user, 'AccountUser.{n}.account_id');
        $department_ids = Hash::extract($user, 'DepartmentUser.{n}.department_id');

        $requiredTraining = $this->TrainingMembership->getRequiredTraining($account_ids,$department_ids,AuthComponent::user('id'));
        $allTraining = $this->TrainingMembership->getAllTraining($account_ids,$department_ids,AuthComponent::user('id'));

		$records = $this->TrainingRecord->findRecords($requiredTraining, AuthComponent::user('id'));
		$allRecords = $this->TrainingRecord->findRecords($allTraining, AuthComponent::user('id'));

        if ($this->request->is('requested')) {
            return $this->request->data;
        }
		#pr($allTraining);
		#exit;
        $this->set('user', $user);
        $this->set('records', $records);
        $this->set('allRecords', $allRecords);
        $this->set('payStatus', $this->User->empPayStatus());
    }



}