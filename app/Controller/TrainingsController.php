<?php
App::uses('AppController', 'Controller');
App::uses('Folder', 'Utility');
App::uses('File', 'Utility');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class TrainingsController extends AppController {

    //public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );
    //Search Plugin

    var $uses = array(
        'User',
        'Training',
        'Setting',
        'TrainingCategory',
        'TrainingClassroom',
        'TrainingRecord',
        'TrainingFile',
        'TrainingClassroomDetail',
        'TrnCat',
        'TrainingMembership',
        'Account',
        'AccountUser',
        'Department',
        'DepartmentUser',
        'AccountDepartment'
    );

    public $components = array( 'RequestHandler', 'Paginator');

    public $presetVars = array(
        array('field' => 'q', 'type' => 'value')
    );

    public $paginate = array(
        'order' => array(
            'Training.name' => 'asc'
        ),
        'limit'=>50
    );

    public function pluginSetup() {
        $user = AuthComponent::user();

        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.Name', 'Training');
    }

    public function beforeFilter() {
        parent::beforeFilter();

        $this->set('title_for_layout', 'Training');
        /*
        $this->set('breadcrumbs', array(
            array('title'=>'Training', 'link'=>array('controller'=>'Trainings', 'action'=>'index')),
        ));
        */
    }

    public function index() {

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        #pr($account_ids);
        #pr($department_ids);
        #pr($user_ids);
        #exit;
        $trainings = $this->TrainingMembership->find('all',array(
			'conditions'=>array(
                'OR' => array(
                    array(
                        'AND'=>array(
                            'TrainingMembership.account_id' => $account_ids,
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.department_id' => $department_ids,
                            'TrainingMembership.is_manditory' => 1
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.user_id' => $user_ids,
                            'TrainingMembership.is_manditory' => 1
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.account_id' => null,
                            'TrainingMembership.department_id' => null,
                            'TrainingMembership.user_id' => null,
                            'TrainingMembership.is_manditory' => 1
                        )
                    ),
                )
            ),
            'contain'=>array(
                'Training'=>array(
				),
				'ReqAcct'=>array(
	            	'fields'=>array(
	                	'ReqAcct.id',
						'ReqAcct.name'
	                )
	            ),
                'ReqDept'=>array(
                    'fields'=>array(
                        'ReqDept.id',
                        'ReqDept.name'
                    )
                ),
                'ReqUser'=>array(
                    'fields'=>array(
                        'ReqUser.id',
                        'ReqUser.first_name',
                        'ReqUser.last_name',
                    )
                )
            ),
            'order'=>array('Training.name'=> 'asc'),
            'group'=>array(
                #'TrainingMembership.training_id',
                #'TrainingMembership.account_id',
            )
        ));

		#pr($trainings);
		#exit;

        if(!empty($this->request->data['Search']['q'])){
            $training_ids = $this->Training->find('list', array(
                'conditions' => array(
                    'OR'=>array(
                        array('Training.name LIKE' => '%'.$this->request->data['Search']['q'].'%', ),
                        array('Training.description LIKE' => '%'.$this->request->data['Search']['q'].'%' )
                    )
                ),
                'contain'=>array(
                ),
                'fields'=>array('Training.id', 'Training.id')
            ));

			$trainings = $this->TrainingMembership->find('all',array(
				'conditions'=>array(
					'AND'=>array(
                        'TrainingMembership.training_id'=>$training_ids
                    ),
	                'OR' => array(
	                    array(
	                        'AND'=>array(
	                            'TrainingMembership.account_id' => $account_ids,
	                        )
	                    ),
	                    array(
	                        'AND'=>array(
	                            'TrainingMembership.department_id' => $department_ids,
	                            'TrainingMembership.is_manditory' => 1
	                        )
	                    ),
	                    array(
	                        'AND'=>array(
	                            'TrainingMembership.user_id' => $user_ids,
	                            'TrainingMembership.is_manditory' => 1
	                        )
	                    ),
	                    array(
	                        'AND'=>array(
	                            'TrainingMembership.account_id' => null,
	                            'TrainingMembership.department_id' => null,
	                            'TrainingMembership.user_id' => null,
	                            'TrainingMembership.is_manditory' => 1
	                        )
	                    ),
	                )
	            ),
	            'contain'=>array(
	                'Training'=>array(
					),
	                'ReqDept'=>array(
	                    'fields'=>array(
	                        'ReqDept.id',
	                        'ReqDept.name'
	                    )
	                ),
                    'ReqAcct'=>array(
	                    'fields'=>array(
	                        'ReqAcct.id',
	                        'ReqAcct.name'
	                    )
	                ),
					'ReqUser'=>array(
	                    'fields'=>array(
	                        'ReqUser.id',
	                        'ReqUser.first_name',
	                        'ReqUser.last_name',
	                    )
	                )
	            ),
	            'order'=>array('Training.name'=> 'asc', 'TrainingMembership.is_manditory'=> 'DESC'),
	            'group'=>array(
	                #'TrainingMembership.training_id',
	                #'TrainingMembership.account_id',
	            )
	        ));
        }

		#pr($trainings);
		#exit;
		$data = array();
        foreach($trainings as $key=>$item){

			$data[$item['TrainingMembership']['training_id']]['Training'] = $item['Training'];

			if($item['TrainingMembership']['is_required'] == 1){
                #$data[$item['TrainingMembership']['training_id']]['TrainingMembership'] = null;
				$data[$item['TrainingMembership']['training_id']]['TrainingMembership']['is_required'] = 1;
			}

			if($item['TrainingMembership']['is_manditory'] == 1){
				#$data[$item['TrainingMembership']['training_id']]['TrainingMembership'] = null;
				$data[$item['TrainingMembership']['training_id']]['TrainingMembership']['is_manditory'] = 1;
			}

			if(!empty($item['ReqDept']['id'])){
				$data[$item['TrainingMembership']['training_id']]['ReqDept'][] = $item['ReqDept'];
			}

			if(!empty($item['ReqUser']['id'])){
				$data[$item['TrainingMembership']['training_id']]['ReqUser'][] = $item['ReqUser'];
			}
        }
        #pr($data);
		#exit;
		$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);
        #$this->set('classRoom', $classRoom);

        $this->set('trainings', $data);
        $this->set('settings', $this->TrainingMembership->required());

    }

	public function details($id=null) {

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

		$this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);

        $trainings = $this->TrainingMembership->find('first', array(
            'conditions'=>array(
                'TrainingMembership.training_id' => $id,
            ),
            'contain'=>array(
                'Training'=>array(
                    'TrainingFile'=>array(

                    ),
					'TrainingRecord'=>array(
                        'conditions'=>array(
                            'TrainingRecord.user_id' => $user_ids
                        ),
                        'User'=>array(
                            'fields'=>array(
                                'User.id',
                                'User.first_name',
                                'User.last_name',
                            ),

                        ),
                        'order'=>array(
                            'TrainingRecord.expires_on' => 'DESC'
                        )
                    )
                ),
            ),

        ));
		#pr($trainings);
		#exit;
        $records = array();
        foreach($trainings['Training']['TrainingRecord'] as $key=>$item){

            if (!array_key_exists($item['user_id'], $records)) {
                $records[$item['user_id']] = $item;
				$records[$item['user_id']]['status'] = 'Current';
				$records[$item['user_id']]['tblrow'] = 'success';

				if(!is_null($item['started_on']) && is_null($item['completed_on'])){
                	$records[$item['user_id']]['status'] = 'in progress';
					$records[$item['user_id']]['tblrow'] = 'info';
                }

                if(strtotime($item['expires_on']) < strtotime('now')){
                	$records[$item['user_id']]['status'] = 'Expired';
					$records[$item['user_id']]['tblrow'] = 'danger';
                }

				if(strtotime($item['expires_on']) >= strtotime('now') && strtotime($item['expires_on']) <= strtotime('+30 days') ){
                	$records[$item['user_id']]['status'] = 'Expiring';
					$records[$item['user_id']]['tblrow'] = 'warning';
                }

				$keysort[$item['user_id']] = $item['User']['first_name'];
        	}
        }

		unset($trainings['Training']['TrainingRecord']);

		if($trainings['TrainingMembership']['is_required'] == 1 || $trainings['TrainingMembership']['is_manditory'] == 1){
			$no_records = array_diff_key($user_ids, $records);

			$no_record = $this->User->find('all', array(
	            'conditions'=>array(
	                'User.id' => $no_records,
	            ),
	            'contain'=>array(

	            ),
				'fields'=>array( 'User.id', 'User.first_name', 'User.last_name' ),
				'order'=>array('User.first_name' => 'DESC', 'User.last_name' => 'DESC' ),
	        ));

			foreach($no_record as $h){
				$records[$h['User']['id']] = $h;
				$records[$h['User']['id']]['expires_on'] = null;
				$records[$h['User']['id']]['status'] = 'No Record Found';
				$records[$h['User']['id']]['tblrow'] = 'danger';
				$keysort[$h['User']['id']] = $h['User']['first_name'];
			}
		}

		$data = array();

		if(!empty($records)){
            array_multisort($keysort, SORT_ASC, $records);

			foreach($records as $m){
				$data[$m['User']['id']] = $m;
			}
        }

        $classRooms = $this->TrainingClassroom->find('all', array(
            'conditions'=>array(
                'TrainingClassroom.account_id' => $account_ids,
                'TrainingClassroom.training_id' => $id,
			),
            'contain'=>array(
                'TrainingClassroomDetail'=>array(
                ),
				'Instructor'=>array()
            ),
            'order'=>array('TrainingClassroom.date' => 'DESC'),
        ));

		$this->set('depts', $this->Department->pickListById($department_ids));
        $this->set('accts', $this->Account->pickListById($account_ids));
        $this->set('users', $this->AccountUser->pickList($account_ids));

		$this->set('trn', $trainings);
        $this->set('records', $data);
        $this->set('classRooms', $classRooms);
        $this->set('settings', $this->TrainingMembership->required());

    }

	public function library($cat=null) {

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

        #pr($training_ids);
        #exit;
        $this->Paginator->settings = array(
            'conditions' => array(

            ),
            'contain'=>array(
                'Author'=>array(
                    'fields'=>array('Author.id', 'Author.first_name', 'Author.last_name')
                ),
                'Account'=>array(
                    'fields'=>array(
                        'Account.id',
                        'Account.name',
                    )
                ),
                'TrainingMembership'=>array(
                    'Account'=>array(
                        'fields'=>array(
                            'Account.id',
                            'Account.name'
                        )
                    ),
                    'Department'=>array(
                        'fields'=>array(
                            'Department.id',
                            'Department.name'
                        )
                    )
                ),
                'TrnCat'=>array(
                    'TrainingCategory'=>array(
                        'fields'=>array(
                            'TrainingCategory.id',
                            'TrainingCategory.name',
                        )
                    ),
                    'fields'=>array(
                        'TrnCat.training_id',
                        'TrnCat.training_category_id',
                    )
                ),
                'TrainingFile'=>array(
                ),
                'TrainingMembership'=>array()
            ),
            'limit' => 100,
            'order'=>array('Training.name'=> 'asc'),
        );

        if(!empty($this->request->data['Search']['q'])){
            $option = array('conditions'=>array(
                'OR'=>array(
                    array('Training.name LIKE' => '%'.$this->request->data['Search']['q'].'%', ),
                    array('Training.description LIKE' => '%'.$this->request->data['Search']['q'].'%' )
                )
            ));

            $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$option);
        }

        if(!is_null($cat)){
            $training_ids = $this->TrnCat->getTrainingIds($cat);

            $options = array(
                'conditions'=>array(
                    'Training.id'=>$training_ids
                )
            );

            $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);
        }

        if(AuthComponent::user('Role.permission_level') <= 40){
            $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

            $options = array(
                'conditions'=>array(
                    'OR'=> array(
                        array(
                            'AND'=>array(
                                'Training.is_active'=>1,
                                'Training.is_public'=>1,
                            ),
                        ),
                        array(
                            'Training.account_id' => $account_ids
                        ),
                    ),
                )
            );

            $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);
        }

        #pr($this->Paginator->settings);
        #exit;

        $trainings = $this->Paginator->paginate('Training');
        #pr($trainings);
        #exit;
        $this->set('trainings', $trainings);
        $this->set('cat', $cat);
        $this->set('trnCat', $this->TrainingCategory->pickList());
    }

    public function view($id=null){
        $options = array();

        if(AuthComponent::user('Role.permission_level') <= 30){
            $option = array('conditions'=>array('TrainingRecord.User.account_id' => $this->Auth->user('account_id'), 'Role.lft >' => $this->Auth->user('Role.lft'), 'Role.rght <' => $this->Auth->user('Role.rght')));
            $options = array_merge_recursive($options,$option);
        }

        $training = $this->request->data = $this->Training->find('first', array(
            'conditions' => array(
                'Training.id' => $id,
            ),
            'contain'=>array(
                'Status'=>array(),
                'TrainingFile'=>array(),
                'TrnCat'=>array(
                    'TrainingCategory'=>array()
                ),
                'TrainingMembership'=>array()
            ),
        ));

        #pr($training);
        #exit;
        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('training', $training);
        $this->set('trnCat', $this->TrainingCategory->pickList());

        $this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);


    }

    public function addToAccount($trn_id=null){
        $this->Training->id = $trn_id;
        if (!$this->Training->exists()) {
            throw new NotFoundException(__('Invalid Training'));
        }

        $c=0;
        $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
        $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
        $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
        $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
        $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];

        if(!empty($this->request->data['Training']['department_id'])){
            foreach($this->request->data['Training']['department_id'] as $dept){
                $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
                $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
                $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
                $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
                $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];
                $this->request->data[$c]['TrainingMembership']['department_id'] = $dept;
                $c++;
            }
        }

        if(!empty($this->request->data['Training']['user_id'])){
            foreach($this->request->data['Training']['user_id'] as $user){
                $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
                $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
                $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
                $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
                $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];
                $this->request->data[$c]['TrainingMembership']['user_id'] = $user;

                $c++;
            }
        }

        unset($this->request->data['Training']);

        if ($this->TrainingMembership->saveAll($this->request->data)) {
            $this->Flash->alertBox(
                'Training Has Been Added To Your Account',
                array( 'params' => array( 'class'=>'alert-success' ))
            );

            $this->redirect(array('controller'=>'Trainings', 'action'=>'index'));
        }else{
            $this->Flash->alertBox(
                'Training Could Not Be Added To Your Account',
                array( 'params' => array( 'class'=>'alert-danger' ))
            );

            $this->redirect(array('controller'=>'Trainings', 'action'=>'library'));
        }
    }

    public function editAccount($trn_id=null){
        $this->Training->id = $trn_id;
        if (!$this->Training->exists()) {
            throw new NotFoundException(__('Invalid Record Id'));
        }

        if ($this->request->is('post') || $this->request->is('put')) {

            $c = 0;

            $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
            $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
            $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
            $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
            $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];

            if(!empty($this->request->data['Training']['department_id'])){
                foreach($this->request->data['Training']['department_id'] as $dept){
                    $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
                    $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
                    $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
                    $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
                    $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];
                    $this->request->data[$c]['TrainingMembership']['department_id'] = $dept;
                    $c++;
                }
            }

            if(!empty($this->request->data['Training']['user_id'])){
                foreach($this->request->data['Training']['user_id'] as $user){
                    $this->request->data[$c]['TrainingMembership']['training_id'] = $this->Training->id;
                    $this->request->data[$c]['TrainingMembership']['renewal'] = $this->request->data['Training']['renewal'];
                    $this->request->data[$c]['TrainingMembership']['created_by'] = AuthComponent::user('id');
                    $this->request->data[$c]['TrainingMembership']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
                    $this->request->data[$c]['TrainingMembership']['is_required'] = $this->request->data['Training']['is_required'];
                    $this->request->data[$c]['TrainingMembership']['user_id'] = $user;

                    $c++;
                }
            }

            //delete all records, recreate
            $this->TrainingMembership->deleteAll(
                array(
                    'TrainingMembership.training_id' => $trn_id,
                    'TrainingMembership.account_id' => $this->request->data['Training']['account_id']
                ),
                false
            );

            unset($this->request->data['Training']);

            if ($this->TrainingMembership->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'Training Settings Have Been Updated',
                    array( 'params' => array( 'class'=>'alert-success' ))
                );
            }else{
                $this->Flash->alertBox(
                    'There Was An Error, Please Try Again!',
                    array( 'params' => array( 'class'=>'alert-danger' ))
                );
            }

            $this->redirect(array('controller'=>'Trainings', 'action'=>'details', $this->Training->id));
        }

        $training = $this->TrainingMembership->find('all', array(
            'conditions' => array(
                'TrainingMembership.training_id' => $trn_id,
                'TrainingMembership.account_id' => AuthComponent::user('AccountUser.0.account_id'),
            ),
            'contain'=>array(
            ),
        ));

        //Get all
        #pr($training);
        #exit;
        $this->request->data['Training']['department_id'] = array();
        $this->request->data['Training']['user_id'] = array();

        foreach($training as $trn){
            $this->request->data['Training']['is_required'] = $trn['TrainingMembership']['is_required'];
            $this->request->data['Training']['renewal'] = $trn['TrainingMembership']['renewal'];
            $this->request->data['Training']['training_id'] = $trn_id;
            $this->request->data['Training']['account_id'] = AuthComponent::user('AccountUser.0.account_id');

            if(!empty($trn['TrainingMembership']['department_id'])){
                $this->request->data['Training']['department_id'][] = $trn['TrainingMembership']['department_id'];
            }

            if(!empty($trn['TrainingMembership']['user_id'])){
                $this->request->data['Training']['user_id'][] = $trn['TrainingMembership']['user_id'];
            }
        }

        #pr($this->request->data);
        #exit;

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);
    }

    public function roster($trn_id = null){
        if ($this->request->is('post') || $this->request->is('put')) {
        }

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('account_ids', $account_ids);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);
        $this->set('training_id', $trn_id);

    }

    public function createClassroom($trn_id = null, $name=null){

        if ($this->request->is('post') || $this->request->is('put')) {
            if(empty($this->request->data['TrainingClassroom']['date'])){
                $this->request->data['TrainingClassroom']['date'] = strtotime('now');
            }else{
                $this->request->data['TrainingClassroom']['date'] = date('Y-m-d', strtotime($this->request->data['TrainingClassroom']['date']));
            }

            if(empty($this->request->data['TrainingClassroomDetail']['user_id'])){
                 $this->Flash->alertBox(
                    'Please Build a Roster!',
                    array(
                        'params' => array(
                            'class'=>'alert-danger'
                        )
                    )
                );

                return $this->redirect(array('controller'=>'Trainings', 'action' => 'details', $trn_id ));

            }

			$renewalDate = (!isset($this->request->data['TrainingClassroom']['renewal'])) ? 12 : $this->request->data['TrainingClassroom']['renewal'];

			$renewalDate = ($renewalDate == 0) ? 240 : $renewalDate;

			foreach($this->request->data['TrainingClassroomDetail']['user_id'] as $k=>$r){

				$this->request->data['TrainingClassroomDetail'][$k]['user_id'] = $r;
                $this->request->data['TrainingClassroomDetail'][$k]['did_attend'] = 1;

				$this->request->data['TrainingRecord'][$k]['user_id'] = $r;
                $this->request->data['TrainingRecord'][$k]['training_id'] = $this->request->data['TrainingClassroom']['training_id'];
                $this->request->data['TrainingRecord'][$k]['trainer_id'] = $this->request->data['TrainingClassroom']['instructor_id'];
                $this->request->data['TrainingRecord'][$k]['date'] = $this->request->data['TrainingClassroom']['date'];
				$this->request->data['TrainingRecord'][$k]['expires_on'] = date("Y-m-d", strtotime(date("Y-m-d", strtotime($this->request->data['TrainingClassroom']['date'])) . " + ". $renewalDate ." months"));
				$this->request->data['TrainingRecord'][$k]['completed_on'] = $this->request->data['TrainingClassroom']['date'];
            }
            unset($this->request->data['TrainingClassroomDetail']['user_id'], $this->request->data['TrainingClassroomDetail']['did_attend']);
            #pr($renewalDate);
			#pr($this->request->data);
            #exit;
			if ($this->TrainingClassroom->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'Training Classroom Has Been Create',
                    array( 'params' => array( 'class'=>'alert-success' ))
                );
            }else{
                $this->Flash->alertBox(
                    'There Was An Error, Please Try Again!',
                    array( 'params' => array( 'class'=>'alert-danger' ))
                );
            }

            return $this->redirect(array('controller'=>'Trainings', 'action' => 'details', $trn_id ));
        }

        $training = $this->Training->find('first', array(
            'conditions' => array(
                'Training.id' => $trn_id,
            ),
            'contain'=>array(
				'TrainingMembership'=>array()
			),
        ));

		$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $user_ids = $this->AccountUser->getAccountIds($account_ids);

        $depts = $this->Department->pickListById($department_ids);
        $accts = $this->Account->pickListById($account_ids);
        $users = $this->AccountUser->pickList($account_ids);

        $this->set('account_ids', $account_ids);
        $this->set('renewal', $training['TrainingMembership'][0]['renewal']);
        $this->set('department_ids', $department_ids);
        $this->set('user_ids', $user_ids);

        $this->set('accts', $accts);
        $this->set('depts', $depts);
        $this->set('users', $users);

        $this->set('training_id', $trn_id);
        $this->set('name', $name.' [ '.date('m/d/Y', strtotime('now')).' ]');
        $this->set('account_id', AuthComponent::user('AccountUser.0.account_id'));

    }

	public function classroomDetails($id = null){
    	$training = $this->TrainingRecord->find('all', array(
            'conditions' => array(
                'TrainingRecord.training_classroom_id' => $id,
            ),
            'contain'=>array(
				'User'=>array(),
				'Trainer' =>array(),
				'Classroom' =>array()
            ),
        ));

		foreach($training as $key=>$trn){
        	$data['Classroom'] = $trn['Classroom'];
			$data['Trainer'] = $trn['Trainer']['first_name'].' '. $trn['Trainer']['last_name'];
			$data['User'][$key] = $trn['User'];
        }

		$this->set('data', $data);

	}

	public function deleteFile($id = null, $trn_id = null){
		$file = $this->TrainingFile->find('first', array(
            'conditions' => array(
                'TrainingFile.id' => $id
            ),
            'contain'=>array(
            ),
            'fields'=>array('TrainingFile.file')
        ));

		$file = '../webroot/files/'.$trn_id.'/'.$file['TrainingFile']['file'];

		if (file_exists($file)) {
			unlink($file);
		}

		$this->TrainingFile->delete($id);

		$training = $this->request->data = $this->Training->find('first', array(
            'conditions' => array(
                'Training.id' => $trn_id
            ),
            'contain'=>array(
                'TrainingFile'=>array()
            ),

        ));
		$trainingFiles = $training['TrainingFile'];

        $this->set(compact('trainingFiles'));

    }

	public function deleteRecord($id = null, $type = null, $user_id = null){
        $this->TrainingRecord->delete($id);

		$user = $this->request->data = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
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

		switch($type){
			case 'required':
				$requiredTraining = $this->TrainingMembership->getRequiredTraining($account_ids,$department_ids,$user_id);
		        $records = $this->TrainingRecord->findRecords($requiredTraining, $user_id);

		        break;

			default:
				$allTraining = $this->TrainingMembership->getAllTraining($account_ids,$department_ids,$user_id);
		        $records = $this->TrainingRecord->findRecords($allTraining, $user_id);
				break;
		}
        $this->set(compact('records'));
        $this->set(compact('user_id'));
    }

	public function getRecord($type = null, $user_id = null){
        $user = $this->request->data = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
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

		switch($type){
			case 'required':
				$requiredTraining = $this->TrainingMembership->getRequiredTraining($account_ids,$department_ids,$user_id);
		        $records = $this->TrainingRecord->findRecords($requiredTraining, $user_id);

		        break;

			default:
				$allTraining = $this->TrainingMembership->getAllTraining($account_ids,$department_ids,$user_id);
		        $records = $this->TrainingRecord->findRecords($allTraining, $user_id);
				break;
		}
        $this->set(compact('records'));
        $this->set(compact('user_id'));
    }

	public function add(){
		if ($this->request->is('post') || $this->request->is('put')) {
			$files = $this->request->data['Training']['files'];
			unset($this->request->data['Training']['files']);

			if(empty($this->request->data['Training']['name'])){
				$this->Flash->alertBox(
                    'Please Enter A Name',
                    array( 'params' => array( 'class'=>'alert-danger' ))
                );

				$this->redirect(array('controller'=>'Trainings', 'action'=>'library'));
			}

			$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
            $this->request->data['Training']['account_id'] = $account_ids[0];
            $this->request->data['Training']['author_id'] = AuthComponent::user('id');

			$memTest = $this->request->data['TrainingMembership'];
            unset($this->request->data['TrainingMembership']);
			if(!empty($memTest['account_id']) || !empty($memTest['department_id']) || !empty($memTest['user_id']) || $memTest['is_manditory'] == 1){

				#$this->TrainingMembership->deleteAll(array('TrainingMembership.training_id' => $this->request->data['Training']['id'], 'TrainingMembership.is_manditory' => 1), false);

				$i = 0;
                if($memTest['is_manditory'] == 1){
					$this->request->data['TrainingMembership']['training_id'] = $this->request->data['Training']['id'];
					$this->request->data['TrainingMembership']['account_id'] = null;
					$this->request->data['TrainingMembership']['department_id'] = null;
					$this->request->data['TrainingMembership']['user_id'] = null;
					$this->request->data['TrainingMembership']['renewal'] = $this->request->data['TrainingMembership']['renewal'];
					$this->request->data['TrainingMembership']['is_manditory'] = 1;
					$this->request->data['TrainingMembership']['created_by'] = AuthComponent::user('id');
				}else{

					if(!empty($memTest['account_id'])){
						foreach($memTest['account_id'] as $v){

							#$this->request->data['TrainingMembership'][$i]['training_id'] = $this->request->data['Training']['id'];
							$this->request->data['TrainingMembership'][$i]['account_id'] = $v;
							$this->request->data['TrainingMembership'][$i]['department_id'] = null;
							$this->request->data['TrainingMembership'][$i]['user_id'] = null;
							$this->request->data['TrainingMembership'][$i]['renewal'] = $memTest['renewal'];
							$this->request->data['TrainingMembership'][$i]['is_manditory'] = 1;
							$this->request->data['TrainingMembership'][$i]['created_by'] = AuthComponent::user('id');

							$i++;
	                    }
					}

					if(!empty($memTest['department_id'])){
						foreach($memTest['department_id'] as $v){
							#$this->request->data['TrainingMembership'][$i]['training_id'] = $this->request->data['Training']['id'];
							$this->request->data['TrainingMembership'][$i]['account_id'] = null;
							$this->request->data['TrainingMembership'][$i]['department_id'] = $v;
							$this->request->data['TrainingMembership'][$i]['user_id'] = null;
							$this->request->data['TrainingMembership'][$i]['renewal'] = $memTest['renewal'];
							$this->request->data['TrainingMembership'][$i]['is_manditory'] = 1;
							$this->request->data['TrainingMembership'][$i]['created_by'] = AuthComponent::user('id');

							$i++;
						}
					}

					if(!empty($memTest['user_id'])){
						foreach($memTest['user_id'] as $v){
							#$this->request->data['TrainingMembership'][$i]['training_id'] = $this->request->data['Training']['id'];
							$this->request->data['TrainingMembership'][$i]['account_id'] = null;
							$this->request->data['TrainingMembership'][$i]['department_id'] = null;
							$this->request->data['TrainingMembership'][$i]['user_id'] = $v;
							$this->request->data['TrainingMembership'][$i]['renewal'] = $memTest['renewal'];
							$this->request->data['TrainingMembership'][$i]['is_manditory'] = 1;
							$this->request->data['TrainingMembership'][$i]['created_by'] = AuthComponent::user('id');

							$i++;
						}
					}

					#pr($this->request->data);
					#exit;
				}
            }

			if ($this->Training->saveAll($this->request->data)) {
				if(!empty($files)){
                    foreach($files as $file){
						$this->upload($file, $this->Training->id, 'edit');
					}
                }
				$this->Flash->alertBox(
                    'Training Has Been Added',
                    array( 'params' => array( 'class'=>'alert-success' ))
                );
            }else{
                $this->Flash->alertBox(
                    'There Was An Error, Please Try Again!',
                    array( 'params' => array( 'class'=>'alert-danger' ))
                );
            }

            $this->redirect(array('controller'=>'Trainings', 'action'=>'library'));

		}

		if(AuthComponent::user('Role.permission_level') >= 60 ){

			$this->set('trnAccount', array());

			$this->set('accts', $this->Account->pickListActive());
        	$this->set('depts', $this->Department->pickList());
        	$this->set('users', $this->User->pickListActive());
		}
	}

	public function edit($id = null){
		if ($this->request->is('post') || $this->request->is('put')) {

        	$files = $this->request->data['Training']['files'];
			unset($this->request->data['Training']['files']);

			if(empty($this->request->data['Training']['name'])){
				$this->Flash->alertBox(
                    'Please Enter A Name',
                    array( 'params' => array( 'class'=>'alert-danger' ))
                );

				$this->redirect(array('controller'=>'Trainings', 'action'=>'library'));
			}

			$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
            $this->request->data['Training']['account_id'] = $account_ids[0];
            $this->request->data['Training']['author_id'] = AuthComponent::user('id');

			$memTest = $this->request->data['TrainingMembership'];
            unset($this->request->data['TrainingMembership']);
			if(!empty($memTest['account_id']) || !empty($memTest['department_id']) || !empty($memTest['user_id']) || $memTest['is_manditory'] == 1){

				$this->TrainingMembership->deleteAll(array('TrainingMembership.training_id' => $this->request->data['Training']['id'], 'TrainingMembership.is_manditory' => 1), false);

				$i = 0;
                if($memTest['is_manditory'] == 1){
					$this->request->data['TrainingMembership']['training_id'] = $this->request->data['Training']['id'];
					$this->request->data['TrainingMembership']['account_id'] = null;
					$this->request->data['TrainingMembership']['department_id'] = null;
					$this->request->data['TrainingMembership']['user_id'] = null;
					$this->request->data['TrainingMembership']['renewal'] = $this->request->data['TrainingMembership']['renewal'];
					$this->request->data['TrainingMembership']['is_manditory'] = 1;
					$this->request->data['TrainingMembership']['created_by'] = AuthComponent::user('id');
				}else{

					if(!empty($memTest['account_id'])){
						foreach($memTest['account_id'] as $v){

							$this->request->data['TrainingMembership'][$i]['training_id'] = $this->request->data['Training']['id'];
							$this->request->data['TrainingMembership'][$i]['account_id'] = $v;
							$this->request->data['TrainingMembership'][$i]['department_id'] = null;
							$this->request->data['TrainingMembership'][$i]['user_id'] = null;
							$this->request->data['TrainingMembership'][$i]['renewal'] = $memTest['renewal'];
							$this->request->data['TrainingMembership'][$i]['is_manditory'] = 1;
							$this->request->data['TrainingMembership'][$i]['created_by'] = AuthComponent::user('id');

							$i++;
	                    }
					}

					if(!empty($memTest['department_id'])){
						foreach($memTest['department_id'] as $v){
							$this->request->data['TrainingMembership'][$i]['training_id'] = $this->request->data['Training']['id'];
							$this->request->data['TrainingMembership'][$i]['account_id'] = null;
							$this->request->data['TrainingMembership'][$i]['department_id'] = $v;
							$this->request->data['TrainingMembership'][$i]['user_id'] = null;
							$this->request->data['TrainingMembership'][$i]['renewal'] = $memTest['renewal'];
							$this->request->data['TrainingMembership'][$i]['is_manditory'] = 1;
							$this->request->data['TrainingMembership'][$i]['created_by'] = AuthComponent::user('id');

							$i++;
						}
					}

					if(!empty($memTest['user_id'])){
						foreach($memTest['user_id'] as $v){
							$this->request->data['TrainingMembership'][$i]['training_id'] = $this->request->data['Training']['id'];
							$this->request->data['TrainingMembership'][$i]['account_id'] = null;
							$this->request->data['TrainingMembership'][$i]['department_id'] = null;
							$this->request->data['TrainingMembership'][$i]['user_id'] = $v;
							$this->request->data['TrainingMembership'][$i]['renewal'] = $memTest['renewal'];
							$this->request->data['TrainingMembership'][$i]['is_manditory'] = 1;
							$this->request->data['TrainingMembership'][$i]['created_by'] = AuthComponent::user('id');

							$i++;
						}
					}

					#pr($this->request->data);
					#exit;
				}
            }

			if ($this->Training->saveAll($this->request->data)) {
				if(!empty($files)){
                    foreach($files as $file){
						$this->upload($file, $this->request->data['Training']['id'], 'edit');
					}
                }
				$this->Flash->alertBox(
                    'Training Has Been Added',
                    array( 'params' => array( 'class'=>'alert-success' ))
                );
            }else{
                $this->Flash->alertBox(
                    'There Was An Error, Please Try Again!',
                    array( 'params' => array( 'class'=>'alert-danger' ))
                );
            }

            $this->redirect(array('controller'=>'Trainings', 'action'=>'library'));
		}

		$training = $this->request->data = $this->Training->find('first', array(
            'conditions' => array(
                'Training.id' => $id
            ),
            'contain'=>array(
                'TrainingFile'=>array(),
                'TrainingMembership'=>array(
					'conditions'=>array(
						'TrainingMembership.is_manditory'=>1
					)
				),
            ),

        ));

		if(AuthComponent::user('Role.permission_level') >= 60 ){
			$trnAccount = $this->TrainingMembership->find('all', array(
	            'conditions' => array(
	                'TrainingMembership.training_id' => $id
	            ),
	            'contain'=>array(
	                'ReqAcct'=>array(
	            		'fields'=>array(
	                		'ReqAcct.id',
							'ReqAcct.name'
		                )
		            ),
	                'ReqDept'=>array(
	                    'fields'=>array(
	                        'ReqDept.id',
	                        'ReqDept.name'
	                    )
	                ),
	                'ReqUser'=>array(
	                    'fields'=>array(
	                        'ReqUser.id',
	                        'ReqUser.first_name',
	                        'ReqUser.last_name',
	                    )
	                )
	            ),
            ));
            #pr($trnAccount);
			#exit;
			$this->set('trnAccount', $trnAccount);

			$this->set('account_ids', Set::extract( $training['TrainingMembership'], '/account_id' ));
			$this->set('department_ids', Set::extract( $training['TrainingMembership'], '/department_id' ));
			$this->set('user_ids', Set::extract( $training['TrainingMembership'], '/user_id' ));

			$this->set('accts', $this->Account->pickListActive());
        	$this->set('depts', $this->Department->pickList());
        	$this->set('users', $this->User->pickListActive());
		}


		#pr($this->request->data);
		#exit;

	}

	public function upload($file=null, $id=null, $type=null){
		$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension
        $arr_ext = array('jpg', 'jpeg', 'gif', 'png', 'mp4', 'ppt', 'zip', 'pdf', 'mp3', 'tiff', 'bmp', 'doc', 'docx', 'pptx', 'txt'); //set allowed extensions

		if($file['error'] == 0 && in_array($ext, $arr_ext)){
			$c = uniqid (rand(), true);;
			$name = $c.'.'.$ext;
            $dir = '../webroot/files/'.$id;

			$uploadfile = $dir.'/'. $name;

			if (!is_dir($dir)) {
			    mkdir($dir, 0777, true);
			}

			$this->request->data['TrainingFile']['human_name'] = $file['name'];
			$this->request->data['TrainingFile']['file'] = $name;
			$this->request->data['TrainingFile']['file_type'] = $ext;
			$this->request->data['TrainingFile']['file_size'] = $file['size'];
			$this->request->data['TrainingFile']['training_id'] = $id;
			if (move_uploaded_file($file['tmp_name'], $uploadfile) == TRUE) {
				$this->TrainingFile->saveAll($this->request->data['TrainingFile']);

				return true;
            }else{
            	return false;
            }
        }

    }

	public function play($id=null){
		$this->Training->id = $id;
        if (!$this->Training->exists()) {
            throw new NotFoundException(__('Invalid Training'));
        }

        $training = $this->request->data = $this->Training->find('first', array(
            'conditions' => array(
                'Training.id' => $id
            ),
            'contain'=>array(
                'TrainingFile'=>array()
            ),

        ));

		$arr_ext = array('jpg', 'jpeg', 'gif', 'png'); //set allowed extensions

		$poster = null;
		$video = null;

		foreach($training['TrainingFile'] as $v){
            if($v['file_type'] == 'mp4'){
				$video = $v['file'];
			}

			if(in_array($v['file_type'], $arr_ext)){
				$poster = $v['file'];
			}
		}
		unset($training['TrainingFile']);

		$this->set('trn', $training);
		$this->set('poster', $poster);
		$this->set('video', $video);
	}

	public function signoff($id=null){
		$trn = $this->TrainingMembership->find('first', array(
            'conditions' => array(
                'TrainingMembership.training_id' => $id
            ),
            'contain'=>array(

            ),

        ));
		$renewalDate = (!isset($trn['TrainingMembership']['renewal'])) ? 12 : $trn['TrainingMembership']['renewal'];
        $renewalDate = ($renewalDate == 0) ? 240 : $renewalDate;
		$date = date("Y-m-d", strtotime('now'));

		$this->request->data['TrainingRecord']['user_id'] = AuthComponent::user('id');
        $this->request->data['TrainingRecord']['training_id'] = $id;
        $this->request->data['TrainingRecord']['trainer_id'] = 145;
        $this->request->data['TrainingRecord']['date'] = $date;
		$this->request->data['TrainingRecord']['expires_on'] = date("Y-m-d", strtotime($date . " + ". $renewalDate ." months"));
		$this->request->data['TrainingRecord']['completed_on'] = $date;
		$this->request->data['TrainingRecord']['started_on'] = $date;

		if ($this->TrainingRecord->save($this->request->data)) {
			$this->Flash->alertBox(
            	'Your Training Has Been Updated',
            	array( 'params' => array( 'class'=>'alert-success' ))
            );
        }else{
        	$this->Flash->alertBox(
            	'There Was An Error, Please Try Again!',
                array( 'params' => array( 'class'=>'alert-danger' ))
            );
        }

        return $this->redirect(array('controller'=>'Dashboard'));

		pr($id);
		exit;
	}

	public function download($id = null) {

        $v = $this->TrainingFile->find('first', array(
            'conditions' => array(
                'TrainingFile.id' => $id,
            ),
            'fields' => array(
				'TrainingFile.file',
				'TrainingFile.training_id',
				'TrainingFile.human_name',
				'TrainingFile.file_type',
			)
        ));

		$file = $v['TrainingFile']['file'];
        $id = $v['TrainingFile']['training_id'];
        $name = $v['TrainingFile']['human_name'];
        #$type = $v['TrainingFile']['file_type'];
        #pr($v);
		#exit;
        if(!is_null($id) && !empty($file)){
			#$this->response->type('application/vnd.ms-powerpointtd>');

            $this->response->file( 'webroot/files/'. $id .'/'. $file, array(
                'download' => true,
                'name' => $name,
            ));

            return $this->response;
        }

        $this->Session->setFlash(__('Please select a file to download'), 'alert-box', array('class'=>'alert-danger'));
        $this->redirect(array('controller'=>'Trainings', 'action' => 'index'));

    }
}