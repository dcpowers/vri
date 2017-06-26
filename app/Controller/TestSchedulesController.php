<?php

App::uses('AppController', 'Controller');

class TestSchedulesController extends AppController {

    public $components = array('Search.Prg', 'RequestHandler', 'Paginator');

    public $uses = array(
        'TestSchedule',
        'AssignedTest',
        'BlindTest',
        'Group',
        'Test',
        'TestRole',
        'GroupMembership',
        'GroupCredit',
        'AssignedTest',
        'User',
        'Short',
		'AccountUser',
		'AccountDepartment',
		'DepartmentUser'
    );

    function beforeFilter() {
        parent::beforeFilter();

    }

    public function delete($id=null, $return_id=null){
        $this->TestSchedule->id = $id;

        if($this->TestSchedule->delete()){

            //Delete Assigned Tests
            $this->AssignedTest->deleteAll(array('AssignedTest.test_schedule_id' => $id), false);
            $this->BlindTest->deleteAll(array('BlindTest.test_schedule_id' => $id), false);

			$this->Flash->alertBox(
            	'Deletetion Successful',
	            array('params' => array('class'=>'alert-success'))
			);

        } else {
			$this->Flash->alertBox(
            	'There Was An Error! Please Try Again.',
	            array('params' => array('class'=>'alert-danger'))
			);
        }

        return $this->redirect(array('controller'=>'tests','action' => 'view_group', $return_id));
    }

    public function Single($id=null){
		$this->User->virtualFields['name'] = 'CONCAT(first_name, " " , last_name)';

        if ($this->request->is('post') || $this->request->is('put')) {

            $email_invite = array();

            foreach($this->request->data['AssignedTest']['user_id'] as $key=>$user){


                $this->request->data[$key]['AssignedTest']['assigned_date'] = date(DATE_MYSQL_DATE);
                $this->request->data[$key]['AssignedTest']['expires_date'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );
                $this->request->data[$key]['AssignedTest']['test_id'] = $this->request->data['AssignedTest']['test_id'];
                $this->request->data[$key]['AssignedTest']['user_id'] = $user;
                $this->request->data[$key]['AssignedTest']['assigned_by_id'] = AuthComponent::user('id');

                $email_invite[$key]['name'] = $this->Test->find('first', array(
                    'conditions' => array('Test.id' => $this->request->data['AssignedTest']['test_id']),
                    'contain' => array(),
                    'fields' => array('Test.name')
                ));

                $email_invite[$key]['user_email'] = $this->User->find('first', array(
                    'conditions' => array('User.id' => $user),
                    'contain' => array(),
                    'fields' => array('User.username', 'User.name', 'User.email')
                ));
            }

            unset($this->request->data['AssignedTest']);
            $this->AssignedTest->create();
            if ($this->AssignedTest->saveMany($this->request->data)) {
                if (env('SERVER_NAME') != 'vri'){
					$this->sendMail($email_invite);
				}
                #Audit::log('Group record added', $this->request->data );
                $this->Flash->alertBox(
            		'Testing has been scheduled!',
	                array('params' => array('class'=>'alert-success'))
				);

				return $this->redirect(array('controller'=>'tests','action' => 'view_single', $id));

            } else {
				$this->Flash->alertBox(
            		'Testing Schedule Error! Please try again.',
	                array('params' => array('class'=>'alert-danger'))
				);

				return $this->redirect(array('controller'=>'tests','action' => 'view_single', $id));
			}
        }

		$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

		#pr($account_ids);
		#exit;
		if(AuthComponent::user('Role.permission_level') >= 30){
            $user_ids = $this->AccountUser->getAccountIds($account_ids, 1);
            #pr($account_ids);
            #pr($user_ids);
			#exit;

			$exempt_list = $this->AssignedTest->find('list', array(
                'conditions' => array(
                    'AssignedTest.user_id' => $user_ids,
                    'AssignedTest.test_id' => $id
                ),
                'contain' => array(
                ),
                'fields'=>array('AssignedTest.user_id')
            ));

			$users = $this->User->find('list', array(
                'conditions' => array(
                    'User.id'=>$user_ids,
                    #'User.id !='=>$exempt_list,
                    'User.is_active'=>1,
                ),
                'contain' => array(

                ),
                'fields'=>array('User.id', 'User.name'),
                'order'=>array('User.name' => 'ASC')

            ));

        }

        $title = "Schedule Testing";

        $this->set( 'users', $users );
        $this->set( 'test_id', $id );
        $this->set( 'title', $title );

        $this->layout = 'blank_nojs';

    }

    public function Group($id=null){
		$acct_id = Set::extract( AuthComponent::user(), '/AccountUser/0/account_id' );

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['TestSchedule']['groups_id'] = $this->request->data['groups_id'];
            unset($this->request->data['groups_id']);

            $id=$this->request->data['TestSchedule']['test_id'];

            if(empty($this->request->data['TestSchedule']['name'])){
                 $info = $this->Test->testDetails($this->request->data['TestSchedule']['test_id']);
                 $this->request->data['TestSchedule']['name'] = $info['Test']['name'] . date(' (M d, Y)', strtotime(date(DATE_MYSQL_DATE)));
            }

            $this->request->data['TestSchedule']['assigned_on'] = date(DATE_MYSQL_DATE);
            $this->request->data['TestSchedule']['expires_on'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );

			$user_list = $this->AccountUser->find('list', array(
                'conditions' => array(
                    'AccountUser.account_id' => $acct_id[0]
                ),
                'contain' => array(
                ),
                'fields'=>array('AccountUser.user_id', 'AccountUser.user_id')
            ));

            $users = $this->DepartmentUser->find('list', array(
                'conditions' => array(
                    'DepartmentUser.department_id' => $this->request->data['TestSchedule']['groups_id'],
                    'DepartmentUser.user_id' => $user_list
                ),
                'contain' => array(
                ),
                'fields'=>array('DepartmentUser.user_id', 'DepartmentUser.user_id')
            ));
            $this->request->data['TestSchedule']['group_id'] = $acct_id[0];

            $this->TestSchedule->create();
            $this->TestSchedule->save($this->request->data['TestSchedule']);
            $test_schedule_id = $this->TestSchedule->id;

            $email_invite = array();
            $key = 0;

			$this->User->virtualFields['fullname'] = 'CONCAT(first_name, " " , last_name)';

            foreach($users as $user){
                $this->request->data[$key]['AssignedTest']['assigned_date'] = date(DATE_MYSQL_DATE);
                $this->request->data[$key]['AssignedTest']['expires_date'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );
                $this->request->data[$key]['AssignedTest']['test_id'] = $this->request->data['TestSchedule']['test_id'];
                $this->request->data[$key]['AssignedTest']['user_id'] = $user;
                $this->request->data[$key]['AssignedTest']['assigned_by_id'] = AuthComponent::user('id');
                $this->request->data[$key]['AssignedTest']['test_schedule_id'] = $test_schedule_id;

                $email_invite[$key]['name'] = $this->Test->find('first', array(
                    'conditions' => array('Test.id' => $this->request->data['TestSchedule']['test_id']),
                    'contain' => array(),
                    'fields' => array('Test.name')
                ));

                $email_invite[$key]['user_email'] = $this->User->find('first', array(
                    'conditions' => array('User.id' => $user),
                    'contain' => array(),
                    'fields' => array('User.username', 'User.fullname')
                ));

                $key++;
            }
            unset($this->request->data['TestSchedule']);
            $this->AssignedTest->create();
            if ($this->AssignedTest->saveMany($this->request->data)) {

                if (env('SERVER_NAME') != 'vri'){
					$this->sendMail($email_invite);
				}
                #Audit::log('Group record added', $this->request->data );
                $this->Session->setFlash(
                    __('Testing has been scheduled!' ),
                    'alert-box',
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('Testing Schdule Error! Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }

            return $this->redirect(array('controller'=>'Tests','action' => 'view_group', 'member'=>true, $id));
        }

        $groups = $this->AccountDepartment->deptListPicks($acct_id[0]);

        $title = "Schedule Testing";

        $this->set( 'groups', $groups );
        $this->set( 'test_id', $id );
        $this->set( 'title', $title );

        $this->layout = 'blank_nojs';

    }

    public function Group_add($id=null, $schedule_id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            $id=$this->request->data['TestSchedule']['test_id'];

            $email_invite = array();
            $key = 0;

            foreach($this->request->data['TestSchedule']['user_id'] as $user){
                $this->request->data[$key]['AssignedTest']['assigned_date'] = date(DATE_MYSQL_DATE);
                $this->request->data[$key]['AssignedTest']['expires_date'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );
                $this->request->data[$key]['AssignedTest']['test_id'] = $this->request->data['TestSchedule']['test_id'];
                $this->request->data[$key]['AssignedTest']['user_id'] = $user;
                $this->request->data[$key]['AssignedTest']['assigned_by_id'] = AuthComponent::user('id');
                $this->request->data[$key]['AssignedTest']['test_schedule_id'] = $this->request->data['TestSchedule']['test_schedule_id'];

                $email_invite[$key]['name'] = $this->Test->find('first', array(
                    'conditions' => array('Test.id' => $this->request->data['TestSchedule']['test_id']),
                    'contain' => array(),
                    'fields' => array('Test.name')
                ));

                $email_invite[$key]['user_email'] = $this->User->find('first', array(
                    'conditions' => array('User.id' => $user),
                    'contain' => array(),
                    'fields' => array('User.username', 'User.fullname')
                ));

                $key++;
            }
            unset($this->request->data['TestSchedule']);

            $this->AssignedTest->create();
            if ($this->AssignedTest->saveMany($this->request->data)) {

                if (env('SERVER_NAME') != 'iwz-3.0'){
                    foreach($email_invite as $invite){
                        $user_email = $invite['user_email']['User']['username'];
                        $user_name = $invite['user_email']['User']['fullname'];
                        $name = $invite['name']['Test']['name'];

                        //Email Link To user
                        $email = new CakeEmail();
                        $email->config('smtp');
                        $email->sender('admin@iworkzone.com', 'iWorkZone Administrator');
                        $email->from(array(AuthComponent::user('username') => AuthComponent::user('fullname')));
                        $email->template('schedule');
                        $email->to(array($user_email => $user_name));

                        $email->subject(AuthComponent::user('fullname').' has scheduled you for the iWorkZone: '.$name);
                        $email->emailFormat('both');

                        $this->set('user_email', $user_email);
                        $this->set('name', $name);
                        $email->viewVars(array('user_email' => $user_email));
                        $email->viewVars(array('name' => $name));

                        $email->send();
                    }
                }
                #Audit::log('Group record added', $this->request->data );
                $this->Session->setFlash(
                    __('User(s) has been scheduled!' ),
                    'alert-box',
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('Testing Schdule Error! Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }

            return $this->redirect(array('controller'=>'Tests','action' => 'view_group', 'member'=>true, $id));
        }

        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );

        if(!empty($supervisorOf_id) ){
            $group_ids = $this->Group->getChildren($supervisorOf_id);
            //get all users in those groups
            $active_user_ids = $this->User->activeUserList($group_ids);

            $searchActive = array();
            foreach($active_user_ids as $key=>$activeId){
                $searchActive[$key] = $activeId['pro_users']['id'];
            }
            $exempt_list = $this->AssignedTest->find('list', array(
                'conditions' => array(
                    'AssignedTest.user_id' => $searchActive,
                    'AssignedTest.test_schedule_id' => $schedule_id
                ),
                'contain' => array(
                ),
                'fields'=>array('AssignedTest.user_id')
            ));

            $users = $this->User->find('list', array(
                'conditions' => array(
                    'User.id'=>$searchActive,
                    'User.id !='=>$exempt_list,
                    'User.is_active'=>1,
                ),
                'contain' => array(

                ),
                'fields'=>array('User.id', 'User.fullname'),
                'order'=>array('User.fullname')

            ));

        }

        $title = "Schedule Testing";

        $this->set( 'users', $users );
        $this->set( 'test_id', $id );
        $this->set( 'title', $title );
        $this->set( 'test_schedule_id', $schedule_id );

        $this->layout = 'blank_nojs';

    }

    public function Type($id=null){
        $title = "Select Type";

        $this->set( 'test_id', $id );
        $this->set( 'title', $title );

        $this->layout = 'blank_nojs';
    }

    public function MultiplePeople($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );

            $group_id = (empty($supervisorOf_id)) ? AuthComponent::user('parent_group_ids.1') : $supervisorOf_id[0] ;
            //Get number of credits needed for that test
            $test_credit = $this->Test->find('list', array(
                'conditions' => array(
                    'Test.id' => $this->request->data['TestSchedule']['test_id']
                ),
                'contain' => array(
                ),
                'fields'=>('Test.credits')
            ));

            $creditCost = $test_credit[$this->request->data['TestSchedule']['test_id']];

            $burn_credit = $this->GroupCredit->useCredit(AuthComponent::user('parent_group_ids.1'), $creditCost);

            if($burn_credit[0] == true){
                $this->Session->setFlash(
                    __('You do not have enough credits to schedule testing.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );

                return $this->redirect(array('controller'=>'tests','action' => 'view', 'member'=>true, $this->request->data['AssignedTest']['test_id']));
            }

            if(empty($this->request->data['TestSchedule']['name'])){
                 $info = $this->User->getUserFullname($this->request->data['TestSchedule'][0]['AssignedTest']['user_id']);
                 $name = $info['User']['fullname'] . date(' (M d, Y)', strtotime(date(DATE_MYSQL_DATE)));
            }else{
                $name = $this->request->data['TestSchedule']['name'];
            }

            $email_invite = array();

            $test_id = $this->request->data['TestSchedule']['test_id'];

            unset($this->request->data['TestSchedule']['test_id'],$this->request->data['TestSchedule']['name']);

            if(!empty(AuthComponent::user()))
            $data = array(
                'name' => $name,
                'test_id' => $test_id,
                'assigned_on' => date(DATE_MYSQL_DATE),
                'expires_on' => date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) ),
                'group_id'=> $group_id
            );
            $this->TestSchedule->create();
            $this->TestSchedule->save($data);

            $test_schedule_id = $this->TestSchedule->id;

            foreach($this->request->data['TestSchedule'] as $key=>$info){
                if(!empty($info['AssignedTest']['test_role_id']) && !empty($info['AssignedTest']['user_id'])){
                    $this->request->data['TestSchedule'][$key]['AssignedTest']['assigned_date'] = date(DATE_MYSQL_DATE);
                    $this->request->data['TestSchedule'][$key]['AssignedTest']['expires_date'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );
                    $this->request->data['TestSchedule'][$key]['AssignedTest']['test_id'] = $test_id;
                    $this->request->data['TestSchedule'][$key]['AssignedTest']['test_schedule_id'] = $test_schedule_id;
                    $this->request->data['TestSchedule'][$key]['AssignedTest']['assigned_by_id'] = AuthComponent::user('id');

                    $email_invite[$key]['name'] = $this->Test->find('first', array(
                        'conditions' => array('Test.id' => $test_id),
                        'contain' => array(),
                        'fields' => array('Test.name')
                    ));

                    $email_invite[$key]['user_email'] = $this->User->find('first', array(
                        'conditions' => array('User.id' => $info['AssignedTest']['user_id']),
                        'contain' => array(),
                        'fields' => array('User.username', 'User.fullname')
                    ));
                }else{
                    unset($this->request->data['TestSchedule'][$key]);
                }
            }

            $this->AssignedTest->create();
            if ($this->AssignedTest->saveall($this->request->data['TestSchedule'])) {
                if (env('SERVER_NAME') != 'iwz-3.0'){
                    foreach($email_invite as $invite){
                        $user_email = $invite['user_email']['User']['username'];
                        $user_name = $invite['user_email']['User']['fullname'];
                        $name = $invite['name']['Test']['name'];

                        //Email Link To user
                        $email = new CakeEmail();
                        $email->config('smtp');
                        $email->sender('admin@iworkzone.com', 'iWorkZone Administrator');
                        $email->from(array(AuthComponent::user('username') => AuthComponent::user('fullname')));
                        $email->template('schedule');
                        $email->to(array($user_email => $user_name));

                        $email->subject(AuthComponent::user('fullname').' has scheduled you for the iWorkZone: '.$name);
                        $email->emailFormat('both');

                        $this->set('user_email', $user_email);
                        $this->set('name', $name);
                        $email->viewVars(array('user_email' => $user_email));
                        $email->viewVars(array('name' => $name));

                        $email->send();
                    }
                }
                #Audit::log('Group record added', $this->request->data );
                $this->Session->setFlash(
                    __('Testing has been scheduled! You have: ' . $burn_credit[1] .' credit(s) left' ),
                    'alert-box',
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('Testing Schedule Error! Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }

            return $this->redirect(array('controller'=>'tests','action' => 'view_group', 'member'=>true, $test_id));
        }

        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );

        if(!empty($supervisorOf_id) ){
            $group_ids = $this->Group->getChildren($supervisorOf_id);
            //get all users in those groups
            $active_user_ids = $this->User->activeUserList($group_ids);

            $searchActive = array();
            foreach($active_user_ids as $key=>$activeId){
                $searchActive[$key] = $activeId['pro_users']['id'];
            }

            $users = $this->User->find('list', array(
                'conditions' => array(
                    'User.id'=>$searchActive,
                    'User.is_active'=>1,
                ),
                'contain' => array(

                ),
                'fields'=>array('User.id', 'User.fullname'),
                'order'=>array('User.fullname')

            ));

        }

        $roles = $this->TestRole->find('list', array(
            'conditions' => array(
                'TestRole.show'=>1
            ),
            'contain' => array(
            ),
            'order'=>array('TestRole.id')

        ));

        $roleRequired = $this->TestRole->find('list', array(
            'conditions' => array(
                'TestRole.show'=>0
            ),
            'contain' => array(
            ),
            'order'=>array('TestRole.id')

        ));

        $title = "Schedule Testing";

        $this->set( 'users', $users );
        $this->set( 'roles', $roles );
        $this->set( 'roleRequired', $roleRequired );
        $this->set( 'test_id', $id );
        $this->set( 'title', $title );

        $this->layout = 'blank_nojs';

    }

    public function MultiplePeople_add($id=null, $schedule_id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );

            $group_id = (empty($supervisorOf_id)) ? AuthComponent::user('parent_group_ids.1') : $supervisorOf_id[0] ;

            $email_invite = array();

            $test_id = $this->request->data['TestSchedule']['test_id'];
            $test_schedule_id = $this->request->data['TestSchedule']['test_schedule_id'];

            unset($this->request->data['TestSchedule']['test_id'],$this->request->data['TestSchedule']['test_schedule_id']);

            foreach($this->request->data['TestSchedule'] as $key=>$info){
                if(!empty($info['AssignedTest']['test_role_id']) && !empty($info['AssignedTest']['user_id'])){
                    $this->request->data['TestSchedule'][$key]['AssignedTest']['assigned_date'] = date(DATE_MYSQL_DATE);
                    $this->request->data['TestSchedule'][$key]['AssignedTest']['expires_date'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );
                    $this->request->data['TestSchedule'][$key]['AssignedTest']['test_id'] = $test_id;
                    $this->request->data['TestSchedule'][$key]['AssignedTest']['test_schedule_id'] = $test_schedule_id;
                    $this->request->data['TestSchedule'][$key]['AssignedTest']['assigned_by_id'] = AuthComponent::user('id');

                    $email_invite[$key]['name'] = $this->Test->find('first', array(
                        'conditions' => array('Test.id' => $test_id),
                        'contain' => array(),
                        'fields' => array('Test.name')
                    ));

                    $email_invite[$key]['user_email'] = $this->User->find('first', array(
                        'conditions' => array('User.id' => $info['AssignedTest']['user_id']),
                        'contain' => array(),
                        'fields' => array('User.username', 'User.fullname')
                    ));
                }else{
                    unset($this->request->data['TestSchedule'][$key]);
                }
            }

            $this->AssignedTest->create();
            if ($this->AssignedTest->saveall($this->request->data['TestSchedule'])) {
                if (env('SERVER_NAME') != 'iwz-3.0'){
                    foreach($email_invite as $invite){
                        $user_email = $invite['user_email']['User']['username'];
                        $user_name = $invite['user_email']['User']['fullname'];
                        $name = $invite['name']['Test']['name'];

                        //Email Link To user
                        $email = new CakeEmail();
                        $email->config('smtp');
                        $email->sender('admin@iworkzone.com', 'iWorkZone Administrator');
                        $email->from(array(AuthComponent::user('username') => AuthComponent::user('fullname')));
                        $email->template('schedule');
                        $email->to(array($user_email => $user_name));

                        $email->subject(AuthComponent::user('fullname').' has scheduled you for the iWorkZone: '.$name);
                        $email->emailFormat('both');

                        $this->set('user_email', $user_email);
                        $this->set('name', $name);
                        $email->viewVars(array('user_email' => $user_email));
                        $email->viewVars(array('name' => $name));

                        $email->send();
                    }
                }
                #Audit::log('Group record added', $this->request->data );
                $this->Session->setFlash(
                    __('Testing has been scheduled!' ),
                    'alert-box',
                    array('class'=>'alert-success')
                );
            } else {
                $this->Session->setFlash(
                    __('Testing Schedule Error! Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }

            return $this->redirect(array('controller'=>'tests','action' => 'view_group', 'member'=>true, $test_id));
        }

        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );

        if(!empty($supervisorOf_id) ){
            $group_ids = $this->Group->getChildren($supervisorOf_id);
            //get all users in those groups
            $active_user_ids = $this->User->activeUserList($group_ids);

            $searchActive = array();
            foreach($active_user_ids as $key=>$activeId){
                $searchActive[$key] = $activeId['pro_users']['id'];
            }

            $users = $this->User->find('list', array(
                'conditions' => array(
                    'User.id'=>$searchActive,
                    'User.is_active'=>1,
                ),
                'contain' => array(

                ),
                'fields'=>array('User.id', 'User.fullname'),
                'order'=>array('User.fullname')

            ));

        }

        $roles = $this->TestRole->find('list', array(
            'conditions' => array(
                'TestRole.show'=>1
            ),
            'contain' => array(
            ),
            'order'=>array('TestRole.id')

        ));

        $roleRequired = $this->TestRole->find('list', array(
            'conditions' => array(
                'TestRole.show'=>0
            ),
            'contain' => array(
            ),
            'order'=>array('TestRole.id')

        ));

        $title = "Schedule Testing";

        $this->set( 'users', $users );
        $this->set( 'roles', $roles );
        $this->set( 'roleRequired', $roleRequired );
        $this->set( 'test_id', $id );
        $this->set( 'test_schedule_id', $schedule_id );
        $this->set( 'title', $title );

        $this->layout = 'blank_nojs';

    }

    public function Blind($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            $id=$this->request->data['TestSchedule']['test_id'];

			if(empty($this->request->data['TestSchedule']['name'])){
                 $info = $this->Test->testDetails($this->request->data['TestSchedule']['test_id']);
                 $this->request->data['TestSchedule']['name'] = $info['Test']['name'] . date(' (M d, Y)', strtotime(date(DATE_MYSQL_DATE)));
            }

            $num = str_pad(mt_rand(1,99999999),5,'0',STR_PAD_LEFT);

            $link = $this->Short->build_url($num);
            #pr(AuthComponent::user());
			#exit;
			$acct_id = Set::extract( AuthComponent::user(), '/AccountUser/0/account_id' );

			$this->request->data['TestSchedule']['link'] = $link;
            $this->request->data['TestSchedule']['link_num'] = $num;
            $this->request->data['TestSchedule']['group_id'] = $acct_id[0];
            $this->request->data['TestSchedule']['assigned_on'] = date(DATE_MYSQL_DATE);
            $this->request->data['TestSchedule']['expires_on'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );

            $this->TestSchedule->create();

            if($this->TestSchedule->save($this->request->data['TestSchedule'])){
                #Audit::log('Group record added', $this->request->data );
                $this->Flash->alertBox(
            		'Testing has been scheduled!',
	                array('params' => array('class'=>'alert-success'))
				);
            } else {
                $this->Session->setFlash(
                    __('Testing Schedule Error! Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );
            }

            return $this->redirect(array('controller'=>'tests','action' => 'view_group', $id));
        }

        $title = "Schedule Testing";

        $this->set( 'test_id', $id );
        $this->set( 'title', $title );

        $this->layout = 'blank_nojs';

    }

    public function scheduleByUser($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            //Get number of credits needed for that test
            $test_credit = $this->Test->find('list', array(
                'conditions' => array(
                    'Test.id' => $this->request->data['Test']['test_id']
                ),
                'contain' => array(
                ),
                'fields'=>('Test.credits')
            ));

            $creditCost = $test_credit[$this->request->data['Test']['test_id']];
            //Get number of Credits
            $credit_id = $this->GroupCredit->find('all', array(
                'conditions' => array(
                    'GroupCredit.group_id' => AuthComponent::user('parent_group_ids')
                ),
                'contain' => array(
                ),
            ));

            if(empty($credit_id[0]['GroupCredit']['credits'])){
                $creditCount = 0;
            }else{
                $creditCount = $credit_id[0]['GroupCredit']['credits'];
            }

            if($creditCount == 0){
                $this->Session->setFlash(
                    __('You do not have enough credits to schedule testing.'),
                    'alert-box',
                    array('class'=>'alert-danger')
                );

                return $this->redirect(array('controller'=>'users','action' => 'view', 'member'=>true, $this->request->data['Test']['user_id'], '#'=>'testing'));
            }

            $newCredits = $credit_id[0]['GroupCredit']['credits'];
            $usedCredits = $credit_id[0]['GroupCredit']['used'];

            $email_invite = array();

            if($creditCount == 0){
                break;
            }

            $this->request->data['AssignedTest']['assigned_date'] = date(DATE_MYSQL_DATE);
            $this->request->data['AssignedTest']['expires_date'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );
            $this->request->data['AssignedTest']['test_id'] = $this->request->data['Test']['test_id'];
            $this->request->data['AssignedTest']['user_id'] = $this->request->data['Test']['user_id'];
            $this->request->data['AssignedTest']['assigned_by_id'] = AuthComponent::user('id');

            $creditCount -= $creditCost;
            $newCredits = $newCredits - $creditCost;
            $usedCredits = $usedCredits + $creditCost;

            $email_invite['name'] = $this->Test->find('first', array(
                'conditions' => array('Test.id' => $this->request->data['Test']['test_id']),
                'contain' => array(),
                'fields' => array('Test.name')
            ));

            $email_invite['user_email'] = $this->User->find('first', array(
                'conditions' => array('User.id' => $this->request->data['Test']['user_id']),
                'contain' => array(),
                'fields' => array('User.username', 'User.fullname')
            ));
            $this->AssignedTest->create();
            if ($this->AssignedTest->save($this->request->data['AssignedTest'])) {

                $groupCreditId = $credit_id[0]['GroupCredit']['id'];
                $data['GroupCredit'] = array('id' => $groupCreditId, 'credits' => $newCredits, 'used' => $usedCredits);

                $this->GroupCredit->save($data);
                //pr(AuthComponent::user());
                //pr($email_invite);
                //exit;
                if (env('SERVER_NAME') != 'iwz-3.0'){
                    $user_email = $email_invite['user_email']['User']['username'];
                    $user_name = $email_invite['user_email']['User']['fullname'];
                    $name = $email_invite['name']['Test']['name'];

                    //Email Link To user
                    $email = new CakeEmail();
                    $email->config('smtp');
                    $email->sender('admin@iworkzone.com', 'iWorkZone Administrator');
                    $email->from(array(AuthComponent::user('username') => AuthComponent::user('fullname')));
                    $email->template('schedule');
                    $email->to(array($user_email => $user_name));

                    $email->subject(AuthComponent::user('fullname').' has scheduled you for the iWorkZone: '.$name);
                    $email->emailFormat('both');

                    $this->set('user_email', $user_email);
                    $this->set('name', $name);
                    $email->viewVars(array('user_email' => $user_email));
                    $email->viewVars(array('name' => $name));

                    $email->send();

                }
                #Audit::log('Group record added', $this->request->data );
                $this->Session->setFlash(
                    __('Testing has been scheduled! You have: ' . $newCredits .' credit(s) left' ),
                    'alert-box',
                    array('class'=>'alert-success'),
                    'user_page'
                );
            } else {
                $this->Session->setFlash(
                    __('Testing Schedule Error! Please try again.'),
                    'alert-box',
                    array('class'=>'alert-danger'),
                    'user_page'
                );
            }
        }
        return $this->redirect(array('controller'=>'users','action' => 'view', 'member'=>true, $this->request->data['Test']['user_id'], '#'=>'testing'));
    }

    public function deleteByUser($id = null) {
        $this->AssignedTest->id = $id;
        //Get number of Credits
        $assigned = $this->AssignedTest->find('all', array(
            'conditions' => array(
                'AssignedTest.id' => $id
            ),
            'contain' => array(
            ),
            'fields'=>array('AssignedTest.complete', 'AssignedTest.test_id', 'AssignedTest.user_id')
        ));
        $test_id = $assigned[0]['AssignedTest']['test_id'];
        $user_id = $assigned[0]['AssignedTest']['user_id'];

        if(is_null($assigned[0]['AssignedTest']['complete'])){
            //Get number of Credits
            $credit_id = $this->GroupCredit->find('all', array(
                'conditions' => array(
                    'GroupCredit.group_id' => AuthComponent::user('parent_group_ids.1')
                ),
                'contain' => array(
                ),
            ));

            //Get number of credits needed for that test
            $test_credit = $this->Test->find('list', array(
                'conditions' => array(
                    'Test.id' => $test_id
                ),
                'contain' => array(
                ),
                'fields'=>('Test.credits')
            ));

            $creditCost = $test_credit[$test_id];

            $groupCreditId = $credit_id[0]['GroupCredit']['id'];
            $newCredits = $credit_id[0]['GroupCredit']['credits'] + $creditCost;
            $usedCredits = $credit_id[0]['GroupCredit']['used'] - $creditCost;

            $data['GroupCredit'] = array('id' => $groupCreditId, 'credits' => $newCredits, 'used' => $usedCredits);

            $this->GroupCredit->save($data);
        }

        if($this->AssignedTest->delete()){

            //if($assigned
            if($test_id == 62){
                $this->TalentpatternUser->deleteAll(array('TalentpatternUser.user_id' => $user_id), false);
            }

            $this->Session->setFlash(
                __('Deletetion Successful'),
                'alert-box',
                array('class'=>'alert-success'),
                'user_page'
            );
        } else {
            $this->Session->setFlash(
                __('There Was An Error! Please Try Again'),
                'alert-box',
                array('class'=>'alert-danger'),
                'user_page'
            );
        }

        return $this->redirect(array('controller'=>'users','action' => 'view', 'member'=>true, $user_id, '#'=>'testing'));
    }

    public function userDelete($id = null, $user_id = null, $return_id=null) {
        $this->AssignedTest->id = $id;
        //Get number of Credits
        $assigned = $this->AssignedTest->find('all', array(
            'conditions' => array(
                'AssignedTest.id' => $id
            ),
            'contain' => array(
            ),
            'fields'=>array('AssignedTest.complete')
        ));

        if($this->AssignedTest->delete()){

            //if($assigned
            if($return_id == 62){
                $this->TalentpatternUser->deleteAll(array('TalentpatternUser.user_id' => $user_id), false);
            }

            $this->Session->setFlash(
                __('Deletetion Successful'),
                'alert-box',
                array('class'=>'alert-success')
            );
        } else {
            $this->Session->setFlash(
                __('There Was An Error! Please Try Again'),
                'alert-box',
                array('class'=>'alert-danger')
            );
        }

        $data = $this->Test->find('first', array(
            'conditions' => array(
                'Test.id' =>$return_id
            ),
            'contain'=>array(
            ),
            'fields'=>array('Test.schedule_type'),
            'order'=>array('Test.name')
        ));

        switch ($data['Test']['schedule_type']){
            case 'Single':
                $type = 'single';
                break;

            default:
                $type = 'group';
                break;
        }
        return $this->redirect(array('controller'=>'tests','action' => 'view_'.$type, $return_id));
    }

    public function singleUserDelete($id = null, $user_id = null, $return_id=null) {
        $this->AssignedTest->id = $id;
        if($this->AssignedTest->delete()){

            $this->Flash->alertBox(
            	'Deletion Successful',
	            array('params' => array('class'=>'alert-success'))
			);
        } else {
            $this->Flash->alertBox(
            	'There Was An Error! Please Try Again.',
	            array('params' => array('class'=>'alert-danger'))
			);
        }

        $data = $this->Test->find('first', array(
            'conditions' => array(
                'Test.id' =>$return_id
            ),
            'contain'=>array(
            ),
            'fields'=>array('Test.schedule_type'),
            'order'=>array('Test.name')
        ));

        switch ($data['Test']['schedule_type']){
            case 'Single':
                $type = 'single';
                break;

            default:
                $type = 'group';
                break;
        }
        return $this->redirect(array('controller'=>'tests','action' => 'view_'.$type, $return_id));
    }

	public function sendMail($email_invite = null){
		if(!is_null($email_invite)){
			foreach($email_invite as $invite){
        		$user_email = $invite['user_email']['User']['email'];
            	$user_name = $invite['user_email']['User']['name'];
                $name = $invite['name']['Test']['name'];

				//Email Link To user
                $email = new CakeEmail();
                $email->config('smtp');
                $email->sender('support@vrifm.com', 'One System Support');
                $email->from(array(AuthComponent::user('email') => AuthComponent::user('fullname')));
                $email->template('schedule');
                $email->to(array($user_email => $user_name));

                $email->subject(AuthComponent::user('first_name').' '. AuthComponent::user('last_name') .' has scheduled you for the One System: '.$name);
                $email->emailFormat('both');

                $this->set('user_email', $user_email);
                $this->set('name', $name);
                $email->viewVars(array('user_email' => $user_email));
                $email->viewVars(array('name' => $name));

                $email->send();
            }
		}
	}
}
?>