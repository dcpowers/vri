<?php
App::uses('AppController', 'Controller');

/**
 * Tests Controller
 *
 * @property
 */
class TestsController extends AppController {

    //public $components = array('Search.Prg');

    //Search Plugin
    public $presetVars = array(
        array('field' => 'q', 'type' => 'value')
    );

    var $paginate = array('AssignedTest'=>array('limit'=>2));

    public $uses = array(
        'Test',
        'AssignedTest',
        'Report.ReportSwitch',
        'Report.Report',
        'Group',
        'GroupMembership',
        'User',
        'TestCategoryType',
        'TestRole',
        'TestSchedule',
        'BlindTest',
		'AccountUser',
		'TalentpatternUser'
    );

    public $components = array('Search.Prg', 'RequestHandler', 'Paginator');

    public function pluginSetup() {
        $user = AuthComponent::user();

        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.Name', 'Tests');
    }

    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(
            'treeview','index'
        );
    }

    public function treeview() {
        $data = $this->Test->generateTreeList();
        #this->set( 'groups', $data );

        #$data = $this->Test->recover();
        #$data = $this->Test->verify();

        #$data = $this->Test->find('all', array(
        #));

        pr($data);
        exit;
        //Use this if tree becomes corrupt
        #$this->Group->recover();
        #$data = $this->Group->verify();
    }

    public function dashboard() {
        $group_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

        $assigned['current'] = $this->AssignedTest->find('all', array(
            'conditions'=>array(
                'AssignedTest.user_id' => AuthComponent::user('id'),
                'OR'=>array(
                    'AssignedTest.complete <' => 100,
                    'AssignedTest.complete IS NULL',
                )
            ),
            'contain'=>array(
                'Test'=>array(
                    'fields'=>array('Test.name')
                ),
                'TestSchedule'=>array(
                    'fields'=>array('TestSchedule.name')
                ),
                'TestRole'=>array(
                    'fields'=>array('TestRole.name')
                )
            ),
            'fields'=>array(
                'AssignedTest.id',
                'AssignedTest.test_id',
                'AssignedTest.assigned_by_id',
                'AssignedTest.assigned_date',
                'AssignedTest.completion_date',
                'AssignedTest.expires_date',
                'AssignedTest.complete',
                'AssignedTest.test_role_id',
                'AssignedTest.test_schedule_id'
            )
        ));

        $assigned['completed'] = $this->AssignedTest->find('all', array(
            'conditions'=>array(
                'AssignedTest.user_id' => AuthComponent::user('id'),
                'AssignedTest.complete >=' => 100,
            ),
            'contain'=>array(
                'Test'=>array(
                    'Report'=>array(
                        'ReportSwitch'=>array(
                            'conditions'=>array(
                                'ReportSwitch.group_id'=>$group_ids
                            ),
                        ),
                        'fields'=>array('Report.is_user_report','Report.action')
                    ),
                    'fields'=>array('Test.name')
                ),
                'TestSchedule'=>array(
                    'fields'=>array('TestSchedule.name')
                ),
                'TestRole'=>array(
                    'fields'=>array('TestRole.name')
                )

            ),
            'fields'=>array(
                'AssignedTest.id',
                'AssignedTest.assigned_by_id',
                'AssignedTest.assigned_date',
                'AssignedTest.completion_date',
                'AssignedTest.expires_date',
                'AssignedTest.complete'
            ),
            'order'=>array('AssignedTest.completion_date DESC')

        ));

        if ($this->request->is('requested')) {
            return $assigned;
        }
    }

    public function index() {
        $account_id = AuthComponent::user('AccountUser.0.account_id');

        $assessments = $this->Test->find('all', array(
            'conditions' => array(
                'Test.parent_id IS NULL',
                'Test.is_active' =>1,
                'Test.schedule_type'=>'Single',
                'OR'=>array(
                    'Test.account_id IS NULL',
                    'Test.account_id'=>$account_id
                )
            ),
            'contain'=>array(
            ),
            'fields'=>array(
				'Test.id',
				'Test.name',
				'Test.description',
				'Test.logo',
				'Test.schedule_type',
				'Test.account_id'
			),
            'order'=>array('Test.name')
        ));

		$this->set( 'assessments', $assessments );

        $surveys = $this->Test->find('all', array(
            'conditions' => array(
                'Test.parent_id IS NULL',
                'Test.is_active' =>1,
                'Test.schedule_type'=>array('Group','Blind'),
                'OR'=>array(
                    'Test.account_id IS NULL',
                    'Test.account_id'=>$account_id
                )
            ),
            'contain'=>array(
            ),
            'fields'=>array(
				'Test.id',
				'Test.name',
				'Test.description',
				'Test.logo',
				'Test.schedule_type',
				'Test.account_id'
			),
            'order'=>array('Test.name')
        ));

        $this->set( 'surveys', $surveys );

        $evaluations = $this->Test->find('all', array(
            'conditions' => array(
                'Test.parent_id IS NULL',
                'Test.is_active' =>1,
                'Test.schedule_type'=>'MultiplePeople',
                'OR'=>array(
                    'Test.account_id IS NULL',
                    'Test.account_id'=>$account_id
                )
            ),
            'contain'=>array(
            ),
            'fields'=>array(
				'Test.id',
				'Test.name',
				'Test.description',
				'Test.logo',
				'Test.schedule_type',
				'Test.account_id'
			),
            'order'=>array('Test.name')
        ));

        $this->set( 'evaluations', $evaluations );
    }

    public function view_single($id=null) {
        if(AuthComponent::user('Role.permission_level') >= 30){
            $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
            $user_ids = $this->AccountUser->getAccountIds($account_ids, 1);
            #pr($data);
            #exit;
            $this->Paginator->settings = array(
                'conditions' => array(
                    'AssignedTest.user_id'=>$user_ids,
                    'AssignedTest.test_id'=>$id
                ),
                'contain'=>array(
                    'Test'=>array(
                        'ReportSwitch'=>array(
                            'conditions'=>array(
                                #'ReportSwitch.group_id' => $account_ids,
                            ),
                            'Report'=>array(
                                'fields'=>array(
                                    'Report.name',
                                    'Report.is_user_report',
                                    'Report.action'
                                )
                            ),
                            'fields'=>array('ReportSwitch.id'),
                        ),
                        'fields'=>array('Test.id', 'Test.name', 'Test.description', 'Test.schedule_type', 'Test.credits', 'Test.logo'),
                    ),
					'User'=>array(
                        'fields'=>array('User.id', 'User.first_name', 'User.last_name'),
                        'order'=>array('User.first_name ASC', 'User.last_name ASC')
                    ),
                ),
                'fields'=>array(
                    'AssignedTest.id',
                    'AssignedTest.user_id',
                    'AssignedTest.assigned_date',
                    'AssignedTest.completion_date',
                    'AssignedTest.expires_date',
                    'AssignedTest.complete'
                ),
                'limit' => 20,

            );
            $data = $this->paginate('AssignedTest');

			if(empty($data)){
                $data = $this->Test->find('all', array(
                    'conditions' => array(
                        'Test.id' =>$id
                    ),
                    'contain'=>array(

                    ),
                    'fields'=>array('Test.id', 'Test.name', 'Test.description', 'Test.schedule_type', 'Test.logo'),
                    'order'=>array('Test.name')
                ));
            }

            $this->set( 'tests', $data );
            #$this->set( 'credits', $this->GroupCredit->getCredits(AuthComponent::user('parent_group_ids')) );
        }
    }

    public function view_group($id=null) {
        if(AuthComponent::user('Role.permission_level') >= 30){
            $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
            $user_ids = $this->AccountUser->getAccountIds($account_ids);

			$this->User->virtualFields['fullname'] = 'CONCAT(first_name, " " , last_name)';

            $this->Paginator->settings = array(
                'conditions' => array(
                    'TestSchedule.group_id'=>$account_ids,
                    'TestSchedule.test_id'=>$id
                ),
                'contain'=>array(
                    'AssignedTest'=>array(
                        'User'=>array(
                            'fields'=>array('User.fullname'),
                        ),
                        'TestRole'=>array(
                            'fields'=>array('TestRole.name')
                        ),
                        'fields'=>array('AssignedTest.id', 'AssignedTest.user_id', 'AssignedTest.complete', 'AssignedTest.test_role_id'),
                    ),
                    'Test'=>array(
                        'ReportSwitch'=>array(
                            'conditions'=>array(
                                'ReportSwitch.group_id' => $account_ids,
                            ),
                            'Report'=>array(
                                'fields'=>array(
                                    'Report.name',
                                    'Report.is_user_report',
                                    'Report.action'
                                )
                            ),
                            'fields'=>array('ReportSwitch.id'),
                        ),
                        'fields'=>array('Test.id', 'Test.name', 'Test.description', 'Test.schedule_type', 'Test.credits'),
                    ),
                    'BlindTest'

                ),
                'limit' => 20,

            );
            $data = $this->paginate('TestSchedule');

            if(empty($data)){
                $data = $this->Test->find('all', array(
                    'conditions' => array(
                        'Test.id' =>$id
                    ),
                    'contain'=>array(

                    ),
                    'fields'=>array('Test.id', 'Test.name', 'Test.description', 'Test.schedule_type', 'Test.credits'),
                    'order'=>array('Test.name')
                ));
            }

            foreach($data as $key=>$item){
                $data[$key]['total_count'] = 0;
                $data[$key]['completed_count'] = 0;
                $data[$key]['showReport'] = false;

                if(isset($item['AssignedTest']) && count($item['AssignedTest']) >= 1){
                    $completed_count=0;

                    foreach($item['AssignedTest'] as $count){
                        if($count['complete'] == 100){
                            $completed_count++;

                            if($count['test_role_id'] == 5 || $count['test_role_id'] == 0){
                                $data[$key]['showReport'] = true;
                            }
                        }

                    }
                    $data[$key]['total_count'] = count($data[$key]['AssignedTest']);
                    $data[$key]['completed_count'] = $completed_count;

                }

                if(isset($item['BlindTest']) && count($item['BlindTest']) >= 1){
                    $completed_count=0;

                    foreach($item['BlindTest'] as $count){
                        if($count['complete'] == 100){
                            $completed_count++;
                            $data[$key]['showReport'] = true;
                        }

                    }
                    $data[$key]['total_count'] = count($data[$key]['BlindTest']);
                    $data[$key]['completed_count'] = $completed_count;

                }
            }

            $this->set( 'tests', $data );
        }
    }

    public function resetTest($id=null, $user_id=null, $test_id=null) {

        $this->request->data['AssignedTest']['id'] = $id;
        $this->request->data['AssignedTest']['test_id'] = $test_id;
        $this->request->data['AssignedTest']['user_id'] = $user_id;
        $this->request->data['AssignedTest']['assigned_date'] = date(DATE_MYSQL_DATE);
        $this->request->data['AssignedTest']['expires_date'] = date(DATE_MYSQL_DATE, strtotime( Configure::read('expired_testing') ) );
        $this->request->data['AssignedTest']['completion_date'] = null;
        $this->request->data['AssignedTest']['t_ans'] = '';
        $this->request->data['AssignedTest']['t_marks'] = '';
        $this->request->data['AssignedTest']['complete'] = 0;

        if ($this->AssignedTest->save($this->request->data)) {

            //Delete talent Pattern if FIT test
            if($test_id == 62){
                $this->TalentpatternUser->deleteAll(array('TalentpatternUser.user_id' => $user_id), false);
            }

            #Audit::log('Group record edited', $this->request->data );
            $this->Flash->alertBox(
            	'Test Reset',
                array('params' => array('class'=>'alert-success'))
            );
		} else {
			$this->Flash->alertBox(
            	'Error, please try again',
                array('params' => array('class'=>'alert-danger'))
            );
        }

        $this->redirect(array('controller'=>'Tests','action'=>'view', $test_id ));

    }

    public function individualReport($id=null) {
        $data = $this->AssignedTest->grabReportData($id);

        $folder = $data[0]['Test']['id'];
        $answers = unserialize($data[0]['AssignedTest']['t_marks']);
        pr($answers);
        exit;
        $this->set( 'id', $data );
        $this->render('reports/'. $folder .'/member_individual');
    }

    public function fix(){
        //set_time_limit(60*60*24);
        //$verify = $this->Test->verify();
        //$this->Test->recover('parent');
        //echo 'recovery completed...';

        //pr($verify);
        //exit;


        $parent = $this->Test->find('first', array(
            'conditions' => array(
                'Test.id' => 40
            ),
            'contain'=>array(
            ),
            'fields'=>array(
                'Test.lft',
                'Test.rght',
                'Test.name',
                'Test.id',

            )
        ));

        $data = $this->Test->find('threaded', array(
            'conditions' => array(
                'Test.lft >=' => $parent['Test']['lft'],
                'Test.rght <=' => $parent['Test']['rght']
            ),
            'contain'=>array(
            ),
            'order'=>array('Test.lft')
            //'fields'=>array('Test.id', 'Test.name', 'Test.description', 'Test.introduction', 'Test.has_subCategories')
        ));

        $this->set('categories', $data);
    }

    public function moveup($id = null, $delta = 14) {
        $this->Test->id = $id;

        if (!$this->Test->exists()) {
           throw new NotFoundException(__('Invalid category'));
        }

        if ($delta > 0) {

            $this->Test->moveUp($this->Test->id, abs($delta));

            $this->Session->setFlash(
                __('worked'),
                'alert-box',
                array('class'=>'alert-success')
            );
        } else {
            $this->Session->setFlash(
                __('Please provide a number of positions the category should be moved up.'),
                'alert-box',
                array('class'=>'alert-danger')
            );

        }

        return $this->redirect(array('action' => 'fix', 'member'=>true));
    }

    public function test($id=null){
        //set_time_limit(60*60*24);
        //$this->Test->reorder($options = array('id'=>15698));
        //$this->Test->recover('parent');
        //echo 'recovery completed...';
        //exit;
        $assignedTest = $this->AssignedTest->getAssignedTestingInfo($id);
        $test = $this->Test->createTest($assignedTest['AssignedTest']['test_id'], $assignedTest['AssignedTest']['t_ans'] );

        if($test[0] == 'complete'){
            $completed = $this->AssignedTest->finishTest($assignedTest['AssignedTest']['id'], $test[1]);

            if ($completed == 1) {

                if($assignedTest['AssignedTest']['test_id'] == 62){
                    $this->TalentpatternUser->saveUserData($assignedTest['AssignedTest']['t_marks']);
                    $this->TalentpatternUser->updateuser();

                }
                #Audit::log('Group record edited', $this->request->data );
                $this->Flash->alertBox(
            		'Congratulations Your Assessment is Finished',
	                array('params' => array('class'=>'alert-success'))
	            );

                //redirect
                $this->redirect(array('controller'=>'dashboard', 'action'=>'index', 'member'=>true));

            } else {
				$this->Flash->alertBox(
            		'There was error, please try again',
	                array('params' => array('class'=>'alert-danger'))
	            );

            }
            $this->set('content', $assignedTest[0]['Test']['conclusion']);
            $this->set('completed', $assignedTest[0]['AssignedTest']['complete']);
            $this->set('questionCount', $test[1]);


        }else{

            $this->set('content', $test);
            $this->set('assignedTest', $assignedTest);
            $this->set('completed', intval($assignedTest['AssignedTest']['complete']));
            $this->set('id', $id);
        }

    }

    public function process(){
        //Add new data to current saved date
        //grab all saved answers and unserlize
        $u=$this->AssignedTest->findByid($this->request->data['testing']['id']);
        if(!empty($this->request->data['t_ans'])){

            $result = array();

            foreach($this->request->data['t_ans'] as $key=>$data){
                $info = $this->Test->getPath($key);
                $path = Hash::extract($info, '{n}.Test.id');
                $countPath = count($path);

                $out = array();
                $curr = &$out;

                if(is_numeric ($data)){
                    $value = $this->Test->grabValue($data);
                }else{
                    $value = $this->Test->grabValue($path[$countPath - 1]);
                }

                $count = count($path);
                $i=1;
                foreach ($path as $item) {
                    $curr[$item] = array();
                    $curr = &$curr[$item];
                    if($i == $count){
                        if(is_numeric ($data)){
                            $curr = $value['Test'];
                        }else{
                            $value['Test']['answerText'] = $value['Test']['answerText'];
                            $value['Test']['answerValue'] = $data;
                            $value['Test']['answerId'] = NULL;

                            $curr = $value['Test'];
                        }
                    }
                    $i++;
                }
                $out = Hash::flatten($out);
                $result = $result + $out;
            }

            unset($data);
            //$result = Hash::expand($result);
            $currentMarks = (empty($u['AssignedTest']['t_ans'])) ? array() : unserialize($u['AssignedTest']['t_ans']);
            $currentMarks = Hash::flatten($currentMarks);
            //merge arrays to together
            $result = array_replace_recursive($currentMarks, $result);

            $totalDone = count($result) / 3;
            $q_count = $this->Test->find('count', array(
                'conditions' => array(
                    'Test.lft >=' =>$u['Test']['lft'],
                    'Test.rght <=' =>$u['Test']['rght'],
                    'Test.category_type'=>3
                ),
                'contain'=>array(
                )
            ));

            $result = Hash::expand($result);

            //serilize and save
            $data['id'] = $u['AssignedTest']['id'];
            $data['complete'] = 100 * round( $totalDone / $q_count , 2);
            $data['t_ans'] = serialize($result);
            $this->AssignedTest->save($data);
        }

        //redirect
        $this->redirect(array('controller'=>'tests', 'action'=>'test', $u['AssignedTest']['id']));
    }

    public function admin_index($id=null) {
        set_time_limit(60*60*24);
        /*$rec = $this->Test->verify();
        if(is_array($rec)){
            set_time_limit(60*60*24);
            $this->Test->recover();
        }*/

        if ( empty($id ) ) {
            $this->set( 'tests', $this->Test->fullList(array('conditions'=>array('Test.parent_id'=>null) ) ) );

            $settings['options'] = $this->Test->statusInt();
            $settings['scheduleType'] = $this->Test->scheduleTypeInt();
            $this->set( 'settings', $settings );

            $this->render('_test_form');

            return;
        }

        $parent = $this->Test->find('first', array('conditions' => array('Test.id' => $id)));

        $data = $this->Test->find('threaded', array(
            'conditions' => array(
                'Test.lft >=' => $parent['Test']['lft'],
                'Test.rght <=' => $parent['Test']['rght']
            ),
            'contain'=>array(
            ),
            'order'=>array('Test.lft')
        ));

        $this->set( 'data', $data );

        $catType = $this->TestCategoryType->find('list', array(
            'conditions' => array(
            ),
            'contain'=>array(
            ),
            'fields'=>array('TestCategoryType.id', 'TestCategoryType.name'),
            'order'=>array('TestCategoryType.id')

        ));
        $this->set( 'testCatType', $catType );
   }

   public function admin_details($id=null) {
        //$this->request->onlyAllow('ajax'); // No direct access via browser URL - Note for Cake2.5: allowMethod()
        $this->autoRender = false;
        $id = $this->request->query('id');
        if (!$id) {
            throw new NotFoundException();
        }
        $data = $this->Test->find('first', array(
            'conditions' => array(
                'Test.id' => $id
            ),
            'contain'=>array(
            ),
        ));

        $parents = $this->Test->getPath($id);

        $data['Test']['Test_id'] = $parents[0]['Test']['id'];
        echo json_encode($data);
        exit;
        //$this->set(compact('data')); // Pass $data to the view
        //$this->set('_serialize', 'data'); // Let the JsonView class know what variable to use

    }

    public function admin_update() {
       if ($this->request->is('post')) {
           /*
           $name = $this->request->data['TestSettings']['name'];
           $id = $this->request->data['TestSettings']['id'];
           $description = $this->request->data['TestSettings']['description'];
           $introduction = $this->request->data['TestSettings']['introduction'];

           $this->request->data['Test']['id'] = $id;
           $this->request->data['Test']['name'] = $name;
           $this->request->data['Test']['description'] = $description;
           $this->request->data['Test']['introduction'] = $introduction;
           */

           if ($this->Test->save($this->request->data['Test'])) {
               echo json_encode( array('success'=>$this->request->data['Test'] ));
               exit;
           } else {
               echo json_encode( array('success'=>false ) );
               exit;
           }
       }
       $this->layout = 'blank_nojs';
   }

    public function admin_view($id=null) {
        $this->Test->id = $id;
        if (!$this->Test->exists()) {
            throw new NotFoundException(__('Invalid Assessment'));
        }

        $this->layout = 'admin';

        $parent = $this->Test->find('first', array('conditions' => array('Test.id' => $id)));

        #$this->Group->recursive = 1;
        $data = $this->Test->find('threaded', array(
            'conditions' => array(
                'Test.lft >=' => $parent['Test']['lft'],
                'Test.rght <=' => $parent['Test']['rght']
            ),
        ));
        //pr($data);
        //exit;
        $this->set( 'data', $data );
    }

    public function admin_resetTallentPattern($id=null, $user_id=null){
        $data = $this->AssignedTest->find('first', array(
            'conditions' => array(
                'AssignedTest.id' => $id
            ),
            'contain'=>array(
            ),
            'fields'=>array('AssignedTest.t_marks', 'AssignedTest.t_ans')
        ));

        if(empty($data['AssignedTest']['t_marks'])){
            $item = $this->Test->interestReportCal(unserialize($data['AssignedTest']['t_ans']));
            $completed = $this->AssignedTest->finishTest($id, $item);

            $results = $item;
        }else{
            $results = unserialize($data['AssignedTest']['t_marks']);
        }

        $save['user_id'] = $user_id;

        $save[strtolower($results[0]['name'])] = $results[0]['avg'];
        $save[strtolower($results[1]['name'])] = $results[1]['avg'];
        $save[strtolower($results[2]['name'])] = $results[2]['avg'];
        $save[strtolower($results[3]['name'])] = $results[3]['avg'];

        $save[strtolower($results[8]['name'])] = $results[8]['avg'];
        $save[strtolower($results[9]['name'])] = $results[9]['avg'];
        $save[strtolower($results[10]['name'])] = $results[10]['avg'];
        $save[strtolower($results[11]['name'])] = $results[11]['avg'];
        $save[strtolower($results[12]['name'])] = $results[12]['avg'];
        $save[strtolower($results[13]['name'])] = $results[13]['avg'];

        $this->TalentpatternUser->deleteAll(array('TalentpatternUser.user_id' => $user_id), false);

        if ($this->TalentpatternUser->save($save)) {

            #Audit::log('Group record edited', $this->request->data );
            $this->Flash->alertBox(
				'Talent Pattern Reset',
	            array('params' => array('class'=>'alert-success'))
	        );

        } else {
            $this->Flash->alertBox(
				'Error, please try again',
	            array('params' => array('class'=>'alert-success'))
	        );
		}

        $this->redirect(array('controller'=>'users','action'=>'view', $user_id, 'admin'=>true ));

    }

    public function inline_edit($id=null) {

        if ($this->request->is('post')) {

            $field = $this->request->data['Test']['field'];
            $id = $this->request->data['Test']['id'];
            $value = $this->request->data['Test']['value'];

            $this->request->data['Test']['id'] = $id;
            $this->request->data['Test'][$field] = $value;

            if ($this->Test->save($this->request->data['Test'])) {
                echo json_encode( array('success'=>true ) );
                exit;
            } else {
                echo json_encode( array('success'=>false ) );
                exit;
            }
        }
        $this->layout = 'blank_nojs';
    }

    public function admin_add() {

        if ($this->request->is('post') || $this->request->is('put')) {
            $error = false;

            if(empty($this->request->data['Test']['name'])){
                $error = true;
            }

            if($error == false){
                $this->request->data['Test']['category_type'] = 1;
                $this->Test->create();
                if ($this->Test->saveall($this->request->data)) {
                    $this->Session->setFlash(__('The Test: "'.$this->request->data['Test']['name'].'" has been saved'), 'alert-box', array('class'=>'alert-success'));

                } else {
                    $this->Session->setFlash(__('The Test could not be saved. Please, try again.'), 'alert-box', array('class'=>'alert-danger'));
                }
            }else{
                $this->Session->setFlash(__('Name Cannot be empty. Please, try again.'), 'alert-box', array('class'=>'alert-danger'));
            }

            $this->redirect(array('controller'=>'tests', 'action'=>'index', 'admin'=>true));
        }

        $settings['options'] = $this->Test->statusInt();
        $settings['scheduleType'] = $this->Test->scheduleTypeInt();
        $this->set( 'settings', $settings );

    }

    public function admin_addSub($parent_id=null, $test_id=null, $cat_type=null) {

        if ($this->request->is('post') || $this->request->is('put')) {
            $error = false;

            $test_id = $this->request->data['Test']['test_id'];

            if(empty($this->request->data['Test']['name']) && $this->request->data['Test']['category_type'] != 4){
                $error = true;
            }

            if($this->request->data['Test']['category_type'] == 4){
                unset($this->request->data['Test']);

                foreach($this->request->data as $key=>$item){
                    if(empty($item['Test']['name'])){
                        unset($this->request->data[$key]);
                    }
                }
            }

            if($error == false && !empty($this->request->data)){
                $this->Test->create();
                if ($this->Test->saveall($this->request->data)) {
                    $this->Session->setFlash(__('Save Successful'), 'alert-box', array('class'=>'alert-success'));

                } else {
                    $this->Session->setFlash(__('Error. Please, try again.'), 'alert-box', array('class'=>'alert-danger'));
                }
            }else{
                $this->Session->setFlash(__('Name Cannot be empty. Please, try again.'), 'alert-box', array('class'=>'alert-danger'));
            }

            $this->redirect(array('controller'=>'tests', 'action'=>'index', $test_id, 'admin'=>true));
        }

        $this->set( 'parent_id', $parent_id );
        $this->set( 'test_id', $test_id );
        $this->set( 'category_type', $cat_type );

    }

    public function admin_movedown($id=null, $test_id = null, $delta = 1) {
        $this->Test->id = $id;
        if (!$this->Test->exists()) {
           throw new NotFoundException(__('Invalid Id'));
        }

        if ($delta > 0) {
            $this->Test->moveDown($this->Test->id, abs($delta));
        } else {
            $this->Session->setFlash(
              'Please provide the number of positions the field should be' .
              'moved down.'
            );
        }

        return $this->redirect(array('controller'=>'tests', 'action'=>'index', $test_id, 'admin'=>true));

    }

    public function admin_moveup($id=null, $test_id = null, $delta = 1) {
        $this->Test->id = $id;
        if (!$this->Test->exists()) {
           throw new NotFoundException(__('Invalid Id'));
        }

        if ($delta > 0) {
            $this->Test->moveUp($this->Test->id, abs($delta));
        } else {
            $this->Session->setFlash(
              'Please provide the number of positions the field should be' .
              'moved down.'
            );
        }

        return $this->redirect(array('controller'=>'tests', 'action'=>'index', $test_id, 'admin'=>true));

    }

    public function admin_delete($id=null, $test_id=null) {
        $this->Test->id = $id;
        if (!$this->Test->exists()) {
           throw new NotFoundException(__('Invalid Id'));
        }

        if($this->Test->delete($this->Test->id)){
            $this->Session->setFlash(__('Deletion Successful'), 'alert-box', array('class'=>'alert-success'));
        }else{
            $this->Session->setFlash(__('Error. Please, try again.'), 'alert-box', array('class'=>'alert-danger'));
        }

        if($test_id == $id){
            return $this->redirect(array('controller'=>'tests', 'action'=>'index', 'admin'=>true));
        }else{
            return $this->redirect(array('controller'=>'tests', 'action'=>'index', $test_id, 'admin'=>true));
        }

    }
}