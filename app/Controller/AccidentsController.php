<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class AccidentsController extends AppController {

    //public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );
    //Search Plugin

    var $uses = array(
        'Application',
        'Accident',
        'AccidentCost',
        'AccidentArea',
		'AccidentFile',
		'AccidentCostLov',
		'AccidentCost',
		'AccidentStatement',
		'User',
        'AccountDepartment',
        'AccountUser',
        'AccidentAreaLov',
		'BingoGame',
		'EmailList'

    );

    public $components = array( 'RequestHandler', 'Paginator', 'Session');

    public $presetVars = array(
        array('field' => 'q', 'type' => 'value')
    );

    public $paginate = array(
        'order' => array(
            'Accident.name' => 'asc'
        ),
        'limit'=>50
    );

    public function pluginSetup() {
        $user = AuthComponent::user();

        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.Name', 'Accidents');
    }

    public function beforeFilter() {
        parent::beforeFilter();

        $this->set('title_for_layout', 'Accidents');
    }

    public function index($status=null) {
        $user_ids = $this->Accident->getUserIds();

		$title = (is_null($status) || $status == 1) ? 'Open' : null ;
		$title = ($status == 2) ? 'Closed' : $title ;
		$title = ($status == 'All') ? 'All' : $title ;

		$status = (is_null($status)) ? 1 : $status ;
		$status = ($status == 'All') ? array(1,2) : $status ;

        #$this->virtualFields['Account.cname'] = 'CONCAT(Account.name, "( ", Account.abr, " )")';
        $this->Paginator->settings = array(
            'conditions' => array(
                'Accident.user_id' => $user_ids,
                'Accident.is_active' => $status,
            ),
            'contain'=>array(
            	'Account'=>array(
					'fields'=>array('Account.abr', 'Account.name')
				),
				'Dept'=>array(
					'fields'=>array('Dept.name')
				)
            ),
            'limit' => 50,
            'order'=>array(
				'Accident.account_id'=> 'asc',
				'Accident.is_active'=> 'asc',
				'Accident.date'=> 'DESC'
			),
        );

		$options = array();
        if(!empty($this->request->data['Search']['q'])){
            $option = array('conditions'=>array(
                'OR'=>array(
                    'Account.name LIKE' => '%'.$this->request->data['Search']['q'].'%',
                    'Account.abr LIKE' => '%'.$this->request->data['Search']['q'].'%'
                )
            ));
            $options = array_merge_recursive($options,$option);
        }

		$this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);

        #pr($this->Paginator->settings);
        #exit;
		
		$result = array();

        $accounts = $this->Paginator->paginate('Accident');
		foreach($accounts as $key=>$t){
			$indexName = $t['Account']['name'] .' ( '. $t['Account']['abr'] .' )';
            $keysort[$indexName] = $indexName;

			unset($t['Account']);
            $value[$indexName][] = $t;

			$result = array_merge($result,$value);

		}

		if(!empty($result)){
            array_multisort($keysort, SORT_ASC, $result);
        }
        #pr($result);
		#exit;
		$this->set('status', $status);
		$this->set('accidents', $result);
		$this->set('title', $title);

    }

    public function view($id=null){
        $this->Accident->id = $id;
        if (!$this->Accident->exists()) {
            throw new NotFoundException(__('Invalid Accident Id'));
        }

        $accident = $this->request->data = $this->Accident->find('first', array(
            'conditions' => array(
                'Accident.id' => $id
            ),
            'contain' => array(
                'Account'=>array(
                    'fields'=>array('Account.id', 'Account.name', 'Account.abr')
                ),
                'Dept'=>array(
                    'fields'=>array('Dept.id', 'Dept.name')
                ),
                'User'=>array(
                    'fields'=>array('User.id', 'User.first_name', 'User.last_name', 'User.doh')
                ),
                'CreatedBy'=>array(),
                'ChangeBy'=>array(),
                'AccidentArea'=>array(
					'AccidentAreaLov'
				),
                'AccidentCost'=>array(
					'AccidentCostLov'=>array(),
					'CreatedBy'=>array()
				),
                'AccidentFile'=>array(
					'CreatedBy'=>array()
				)
            ),

        ));
        $this->set('accident', $accident);
        $this->set('setting', $this->Accident->yesNo());
        $this->set('status', $this->Accident->statusInt());
    }

    public function employeeView($id=null, $pageStatus = null, $viewBy = null){
        $this->Account->id = $id;
        if (!$this->Account->exists()) {
            throw new NotFoundException(__('Invalid Account Id'));
        }

        $deptClass= null;
        $superClass= null;
        $roleClass= null;

        $aStatusClass = null;
        $iStatusClass = null;
        $allStatusClass = null;

        $viewBy = (is_null($viewBy)) ? 'department' : $viewBy ;
        $this->set('viewBy', $viewBy);

        if(is_null($pageStatus) || $pageStatus == 1){
            $pageStatus = 1;
            $setStatus = 1;
            $aStatusClass = 'active';
        }else if($pageStatus == 2){
            $pageStatus = 2;
            $setStatus = 2;
            $iStatusClass = 'active';
        }

        if($pageStatus == 'all'){
            $pageStatus = array(1,2);
            $setStatus = 'all';
            $allStatusClass = 'active';
        }

        $this->set('pageStatus', $setStatus);

        $account = $this->request->data = $this->Account->find('first', array(
            'conditions' => array(
                'Account.id' => $id
            ),
            'contain' => array(
                'Manager'=>array(
                    'fields'=>array('Manager.id', 'Manager.first_name', 'Manager.last_name')
                ),
                'Coordinator'=>array(
                    'fields'=>array('Coordinator.id', 'Coordinator.first_name', 'Coordinator.last_name')
                ),
                'RegionalAdmin'=>array(
                    'fields'=>array('RegionalAdmin.id', 'RegionalAdmin.first_name', 'RegionalAdmin.last_name')
                ),
                'Status'=>array(),
                'AccountDepartment'=>array(
                    'Department'=>array(
                        'fields'=>array(
                            'Department.name'
                        )
                    )
                ),
                'User'=>array(
                    'conditions'=>array(
                        'User.is_active'=>$pageStatus
                    ),
                    'Status'=>array(
                        'fields'=>array('Status.name', 'Status.color', 'Status.icon')
                    ),
                    'Supervisor'=>array(
                        'Status'=>array(
                            'fields'=>array('Status.name', 'Status.color')
                        )
                    ),
                    'Role'=>array(
                        'fields'=>array('Role.name', 'Role.lft')
                    ),
                    'DepartmentUser'=>array(
                        'Department'=>array(
                            'fields'=>array('Department.name', 'Department.abr')
                        ),
                    ),
                    'fields'=>array(
                        'User.id',
                        'User.first_name',
                        'User.last_name',
                        'User.username',
                        'User.email',
                    ),
                    'order'=>array(
                        'User.first_name'=>'asc',
                        'User.last_name'=>'asc',
                    ),

                )
            ),

        ));

        $dept_ids = $this->request->data['AccountDepartment']['department_id'] = Set::extract( $account['AccountDepartment'], '/department_id' );

        $users = array();

        foreach($account['User'] as $data){
            switch($viewBy){
                case 'supervisor':
                    if(array_key_exists('first_name', $data['Supervisor'])){
                        $indexName = $data['Supervisor']['first_name'].' '. $data['Supervisor']['last_name'].'<small> [ '.$data['Supervisor']['Status']['name'].' ]</small>';
                        $keysort[$indexName] = $data['Supervisor']['first_name'];
                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                    }
                    $superClass = 'active';
                    $value[$indexName][] = $data;
                    break;

                case 'role':
                    $indexName = $data['Role']['name'];
                    $keysort[$indexName] = $data['Role']['lft'];
                    $roleClass = 'active';
                    $value[$indexName][] = $data;
                    break;

                case 'department':
                default:
                    if(!empty($data['DepartmentUser'])){
                        foreach($data['DepartmentUser'] as $newItem){
                            $indexName = $newItem['Department']['name'].' ( '. $newItem['Department']['abr'] .' )';
                            $keysort[$indexName] = $newItem['Department']['name'];

                            $value[$indexName][] = $data;
                        }

                    }else{
                        $indexName = '--';
                        $keysort[$indexName] = '--';
                        $value[$indexName][] = $data;
                    }
                    $deptClass = 'active';
                    break;

            }

            #$value[$indexName][] = $data;

            $users = array_merge($users,$value);
        }
        #pr($users);
        if(!empty($account['User'])){
            array_multisort($keysort, SORT_ASC, $users);
        }
        #pr($users);
        #exit;
        unset($account['User']);

        $corp_emp_ids = $this->AuthRole->pickListByRole(AuthComponent::user('Role.id'));

        $userList['Vanguard Resources'] = $this->User->pickListByRole($corp_emp_ids);
        $userList[$account['Account']['name']] = $this->User->pickListByAccount($id);

        $status = $this->Setting->pickList('status');
        $departments = $this->Department->pickList();

        $this->set(compact(
            'account',
            'users',
            'userList',
            'status',
            'departments',
            'superClass',
            'deptClass',
            'roleClass',
            'aStatusClass',
            'iStatusClass',
            'allStatusClass'
        ));
    }

    public function edit($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {

            $this->request->data['Accident']['date'] = (!empty($this->request->data['Accident']['date'])) ? date('Y-m-d', strtotime($this->request->data['Accident']['date'])) : date('Y-m-d', strtotime('now')) ;
			$this->AccidentArea->deleteAll(array('AccidentArea.accident_id' => $this->request->data['Accident']['id']), false);
            $c = 0;
            if(!empty($this->request->data['AccidentArea']['accident_area_lov_id'])){
                foreach ($this->request->data['AccidentArea']['accident_area_lov_id'] as $account_id){
                    $this->request->data['AccidentArea'][$c]['accident_id'] = $this->request->data['Accident']['id'];
                    $this->request->data['AccidentArea'][$c]['accident_area_lov_id'] = $account_id;
                    $c++;
                }

                unset($this->request->data['AccidentArea']['accident_area_lov_id']);
            }
            #pr($this->request->data);
			#exit;
			if(empty($this->request->data['Accident']['description'])){
				$this->Flash->alertBox(
	            	'Please Enter A Description Of What Happened',
	                array( 'params' => array( 'class'=>'alert-danger' ))
	            );

				$this->redirect(array('controller'=>'Accidents', 'action'=>'view', $this->request->data['Accident']['id']));
			}

			$this->request->data['Accident']['change_by'] = AuthComponent::user('id');
			#$this->request->data['Accident']['modified'] = false;
			#pr($this->request->data);
			#exit;

			if ($this->Accident->saveAll($this->request->data)) {
            	#Audit::log('Group record added', $this->request->data );

				$this->Flash->alertBox(
	            	'Accident Report Updated',
	                array( 'params' => array( 'class'=>'alert-success' ))
	            );
            }else{
            	$this->Flash->alertBox(
	            	'There Were Problems, Please Try Again',
	                array( 'params' => array( 'class'=>'alert-danger' ))
	            );
            }

			$this->redirect(array('controller'=>'Accidents', 'action'=>'view', $this->request->data['Accident']['id']));
        }

        $accident = $this->request->data = $this->Accident->find('first', array(
            'conditions' => array(
                'Accident.id' => $id
            ),
            'contain' => array(
            	'AccidentArea'=>array(
					'AccidentAreaLov'=>array(),
                ),
				'User'=>array(
				)
        	),

        ));

		$this->set('areas', $this->AccidentAreaLov->pickList());
		#pr($accident);
		#exit;
    }

    public function add($id=null){
        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
        $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );

        if ($this->request->is('post') || $this->request->is('put')) {
            $this->request->data['Accident']['date'] = (!empty($this->request->data['Accident']['date'])) ? date('Y-m-d', strtotime($this->request->data['Accident']['date'])) : date('Y-m-d', strtotime('now')) ;

			$user = $this->User->find('first', array(
	            'conditions' => array(
	                'User.id' => $this->request->data['Accident']['user_id']
	            ),
	            'contain'=>array(
					'AccountUser'=>array(
	                ),
					'DepartmentUser'=>array(
	                ),
	            ),
				'fields'=>array('User.first_name', 'User.last_name', 'User.doh')

	        ));
			#pr($user);
			$this->request->data['Accident']['first_name'] = $user['User']['first_name'];
			$this->request->data['Accident']['last_name'] = $user['User']['last_name'];
			$this->request->data['Accident']['doh'] = $user['User']['doh'];
			$this->request->data['Accident']['created_by'] = AuthComponent::user('id');
			$this->request->data['Accident']['account_id'] = $user['AccountUser'][0]['account_id'];
			$this->request->data['Accident']['department_id'] = $user['DepartmentUser'][0]['department_id'];

            $c = 0;
            if(!empty($this->request->data['AccidentArea']['accident_area_lov_id'])){
                foreach ($this->request->data['AccidentArea']['accident_area_lov_id'] as $account_id){
                    $this->request->data['AccidentArea'][$c]['accident_area_lov_id'] = $account_id;
                    $c++;
                }

                unset($this->request->data['AccidentArea']['accident_area_lov_id']);
            }
            #pr($this->request->data);
			#exit;
			if(empty($this->request->data['Accident']['description'])){
				$this->Flash->alertBox(
	            	'Please Enter A Description Of What Happened',
	                array( 'params' => array( 'class'=>'alert-danger' ))
	            );

				$this->redirect(array('controller'=>'Accidents', 'action'=>'index'));
			}

			if ($this->Accident->saveAll($this->request->data)) {
            	#Audit::log('Group record added', $this->request->data );
				if (env('SERVER_NAME') == 'vrifm.com'){
					$this->send_mail($this->Accident->id);
				}

				$now = date('Y-m-d', strtotime('now'));
                $this->BingoGame->updateAll(
				    array( 'BingoGame.end_date' => "' $now '", 'BingoGame.amount' => 0 ),   //fields to update
				    array('BingoGame.end_date' => null, 'BingoGame.account_id' =>  AuthComponent::user('AccountUser.0.account_id'))  //condition
				);

				$this->request->data['BingoGame']['start_date'] = date('Y-m-d', strtotime('now'));
				$this->request->data['BingoGame']['account_id'] = AuthComponent::user('AccountUser.0.account_id');
				$this->BingoGame->saveAll($this->request->data);

				$this->Flash->alertBox(
	            	'A New Accident Has Been Reported',
	                array( 'params' => array( 'class'=>'alert-success' ))
	            );
            }else{
            	$this->Flash->alertBox(
	            	'There Were Problems, Please Try Again',
	                array( 'params' => array( 'class'=>'alert-danger' ))
	            );
            }

			$this->redirect(array('controller'=>'Accidents', 'action'=>'view', $this->Accident->id));
        }

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $this->set('userList', $this->AccountUser->pickList($account_ids));
        $this->set('areas', $this->AccidentAreaLov->pickList());
    }

	public function sendRequest($id=null, $s_id=null){
		$this->Accident->id = $id;

		if (!$this->Accident->exists()) {
	    	throw new NotFoundException(__('Invalid Accident Id'));
	    }

	    $accident = $this->Accident->find('first', array(
	    	'conditions' => array(
	        	'Accident.id' => $id
	        ),
	        'contain' => array(
	        	'User'=>array(
	            	'fields'=>array('User.id', 'User.first_name', 'User.last_name')
	            ),
				'Account'=>array(
                    'fields'=>array('Account.id', 'Account.name', 'Account.abr')
                ),
	        ),
        ));

		if ($this->request->is('post') || $this->request->is('put')) {
            $c=0;

			$type = ($s_id == 1) ? 'Employee' : 'Supervisor' ;
			foreach($this->request->data['Accident']['user_id'] as $user_id){
				$user = $this->User->find('first', array(
		            'conditions' => array(
		                'User.id' => $user_id
		            ),
		            'contain' => array(
		            ),
					'fields'=>array('User.id', 'User.first_name', 'User.last_name')
		        ));

				$this->request->data[$c]['AccidentFile']['accident_id'] = $id;
				$this->request->data[$c]['AccidentFile']['name'] = 'On-line '. $type .' Statement';
				$this->request->data[$c]['AccidentFile']['description'] = 'On-line '. $type .' Statement By: '.$user['User']['first_name'] .' '.$user['User']['last_name'];
	            $this->request->data[$c]['AccidentFile']['created_by'] = $user['User']['id'];
				$this->request->data[$c]['AccidentFile']['date'] = date('Y-m-d', strtotime('now'));
				$this->request->data[$c]['AccidentFile']['is_active'] = 1;
				$this->request->data[$c]['AccidentFile']['statement_id'] = $s_id;

				$c++;
			}

			unset($this->request->data['Accident']);

            #pr($this->request->data);
			#exit;

			if ($this->AccidentFile->saveAll($this->request->data)) {
            	#Audit::log('Group record added', $this->request->data );
                $this->Flash->alertBox(
	            	'A Request Has Been Sent',
	                array( 'params' => array( 'class'=>'alert-success' ))
	            );
            }else{
            	$this->Flash->alertBox(
	            	'There Were Problems, Please Try Again',
	                array( 'params' => array( 'class'=>'alert-danger' ))
	            );
            }

			$this->redirect(array('controller'=>'Accidents', 'action'=>'view', $id));
        }

		$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

        $this->set('userList', $this->AccountUser->pickList($accident['Account']['id']));
        $this->set('accident_id', $id);
        $this->set('statement_id', $s_id);

	}

	public function cost($id=null, $record_id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            #pr($user);
			$this->request->data['AccidentCost']['created_by'] = AuthComponent::user('id');
			if ($this->AccidentCost->saveAll($this->request->data)) {
            	#Audit::log('Group record added', $this->request->data );
                $this->Flash->alertBox(
	            	'Accident Cost Has Been Added',
	                array( 'params' => array( 'class'=>'alert-success' ))
	            );
            }else{
            	$this->Flash->alertBox(
	            	'There Were Problems, Please Try Again',
	                array( 'params' => array( 'class'=>'alert-danger' ))
	            );
            }

			$this->redirect(array('controller'=>'Accidents', 'action'=>'view', $this->request->data['AccidentCost']['accident_id']));
        }

        $this->set('costLov', $this->AccidentCostLov->pickList());
        $this->request->data['Accident']['id'] = $id;
		if(!empty($record_id)){
			$this->request->data = $this->AccidentCost->get_info($record_id);
			$this->request->data['AccidentCost']['id'] = $record_id;
		}
    }

	public function files($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            #pr($this->request->data);
			$c=0;
			$id = $this->request->data['Accident']['accident_id'];
			foreach($this->request->data['AccidentFile'] as $v){
				if($v['files']['error'] == 0){
					$this->request->data[$c]['AccidentFile']['name'] = $this->upload($v['files'], $id);
					$this->request->data[$c]['AccidentFile']['created_by'] = AuthComponent::user('id');
					$this->request->data[$c]['AccidentFile']['accident_id'] = $this->request->data['Accident']['accident_id'];
					$this->request->data[$c]['AccidentFile']['description'] = $v['description'];
					$this->request->data[$c]['AccidentFile']['date'] = date('Y-m-d', strtotime('now'));

					$c++;
				}

			}
			unset(
				$this->request->data['Accident'],
				$this->request->data['AccidentFile']
			);
			#pr($this->request->data);
			#exit;
			if ($this->AccidentFile->saveAll($this->request->data)) {
            	#Audit::log('Group record added', $this->request->data );
                $this->Flash->alertBox(
	            	'Files Have Been Added',
	                array( 'params' => array( 'class'=>'alert-success' ))
	            );
            }else{
            	$this->Flash->alertBox(
	            	'There Were Problems, Please Try Again',
	                array( 'params' => array( 'class'=>'alert-danger' ))
	            );
            }

			$this->redirect(array('controller'=>'Accidents', 'action'=>'view', $id));
        }

        $this->set('costLov', $this->AccidentCostLov->pickList());
        $this->request->data['Accident']['id'] = $id;
    }



    public function delete($id = null) {
        $this->Accident->id = $id;

		if($this->Accident->delete()){
			$this->Flash->alertBox(
            	'Accident Has Been Deleted',
	            array('params' => array('class'=>'alert-success'))
			);
        }else{
			$this->Flash->alertBox(
            	'There Was An Error! Please Try Again',
	            array('params' => array('class'=>'alert-danger'))
			);
        }

        return $this->redirect(array('controller'=>'Accidents','action' => 'index'));
    }

	public function deleteAccidentFile($id = null, $accident_id = null) {
        $this->AccidentFile->id = $id;

		if($this->AccidentFile->delete()){
        	$this->Flash->alertBox(
            	'Accident File Deleted',
	            array('params' => array('class'=>'alert-success'))
			);
        }else{
			$this->Flash->alertBox(
            	'There Was An Error! Please Try Again',
	            array('params' => array('class'=>'alert-danger'))
			);

        }


        return $this->redirect(array('controller'=>'Accidents','action' => 'view', $accident_id));
    }

	public function deleteCostFile($id = null, $accident_id = null) {
        $this->AccidentCost->id = $id;

		if($this->AccidentCost->delete()){
        	$this->Flash->alertBox(
            	'Record Deleted',
	            array('params' => array('class'=>'alert-success'))
			);
        }else{
			$this->Flash->alertBox(
            	'There Was An Error! Please Try Again',
	            array('params' => array('class'=>'alert-danger'))
			);

        }


        return $this->redirect(array('controller'=>'Accidents','action' => 'view', $accident_id));
    }

	public function getDashboard(){
		$v = $this->AccidentFile->find('all', array(
            'conditions' => array(
                'AccidentFile.created_by' => AuthComponent::user('id'),
                'AccidentFile.is_active' => 1,
            ),
			'contain'=>array(
				'Accident'=>array(
                	'fields'=>array(
						'Accident.first_name',
						'Accident.last_name',
					)
				),
			),

        ));

		return $v;
	}

	public function upload($file=null, $id=null, $type=null){
		$ext = substr(strtolower(strrchr($file['name'], '.')), 1); //get the extension

		if($file['error'] == 0){
			$c = uniqid (rand(), true);;
			$name = $file['name'];
            $dir = '../webroot/files/accidents/'.$id;

			$uploadfile = $dir.'/'. $name;

			if (!is_dir($dir)) {
			    mkdir($dir, 0777, true);
			}

			if (move_uploaded_file($file['tmp_name'], $uploadfile) == TRUE) {
				#$this->TrainingFile->saveAll($this->request->data['TrainingFile']);
                return $name;
            }else{
            	return false;
            }
        }

    }

	public function empStatement($id = null){

		$this->set('options', $this->Accident->yesNo());
    	$this->set('accident_id', $id);
	}

	public function supervisorStatement($id = null){

		$this->set('options', $this->Accident->yesNo());
    	$this->set('accident_id', $id);
	}

	public function open($id = null, $s = null){
		$this->request->data['Accident']['id'] = $id;
		$this->request->data['Accident']['is_active'] = 1;

		if ($this->Accident->saveAll($this->request->data)) {
			$this->Flash->alertBox(
	        	'Accident Has Been Opened',
	            array( 'params' => array( 'class'=>'alert-success' ))
	        );
        }else{
        	$this->Flash->alertBox(
	        	'There Were Problems, Please Try Again',
	            array( 'params' => array( 'class'=>'alert-danger' ))
	        );
        }

		$this->redirect(array('controller'=>'Accidents', 'action'=>'index', $s));
	}

	public function close($id = null, $s = null){
		$this->request->data['Accident']['id'] = $id;
		$this->request->data['Accident']['is_active'] = 2;

		if ($this->Accident->saveAll($this->request->data)) {
			$this->Flash->alertBox(
	        	'Accident Has Been Closed',
	            array( 'params' => array( 'class'=>'alert-success' ))
	        );
        }else{
        	$this->Flash->alertBox(
	        	'There Were Problems, Please Try Again',
	            array( 'params' => array( 'class'=>'alert-danger' ))
	        );
        }

		$this->redirect(array('controller'=>'Accidents', 'action'=>'index', $s));
	}

	public function download($id = null) {

        $v = $this->AccidentFile->find('first', array(
            'conditions' => array(
                'AccidentFile.id' => $id,
            ),
            'contain'=>array()
        ));
		
		$id = $v['AccidentFile']['accident_id'];
        $name = $v['AccidentFile']['name'];
        #$type = $v['TrainingFile']['file_type'];
        #pr($v);
		#exit;
		if(!empty($v['AccidentFile']['statement_id'])){
			$answers = unserialize($v['AccidentFile']['statement']);
			
			foreach($answers as $key=>$item){
				$s = $this->AccidentStatement->find('first', array(
		            'conditions' => array(
		                'AccidentStatement.id' => $key,
		            ),
		            'contain'=>array()
		        ));	
		        pr($s);
		        pr($item);
		        exit;
			}
			pr($answers);
			pr($v);
			exit;
			
			
		}else{
			if(!is_null($id) && !empty($name)){
				#$this->response->type('application/vnd.ms-powerpointtd>');

            	$this->response->file( 'webroot/files/accidents/'. $id .'/'. $name, array(
                	'download' => true,
                	'name' => $name,
            	));

            	return $this->response;
			}
        }

        $this->Session->setFlash(__('Please select a file to download'), 'alert-box', array('class'=>'alert-danger'));
        $this->redirect(array('controller'=>'accidents', 'action' => 'index'));

    }
    
    public function viewStatement($id = null) {

        $v = $this->AccidentFile->find('first', array(
            'conditions' => array(
                'AccidentFile.id' => $id,
            ),
            'contain'=>array(
            	'CreatedBy'=>array()
            )
        ));
		$info['name'] = $v['AccidentFile']['name'];
		$info['user'] = $v['CreatedBy']['first_name'].' '.$v['CreatedBy']['last_name'];
		$options = $this->AccidentStatement->yesNo();
		
		$answers = unserialize($v['AccidentFile']['statement']);
		$c = 0;
		foreach($answers as $key=>$item){
			$s = $this->AccidentStatement->find('first', array(
		        'conditions' => array(
		            'AccidentStatement.id' => $key,
		        ),
		        'contain'=>array()
		    ));
		    #pr($s);
		    #exit;
		    $data[$c]['statement'] = $s['AccidentStatement']['name'];
		    $data[$c]['answer'] = ( $s['AccidentStatement']['type'] == 'select' && !empty($item)) ? $options[$item] : $item;
		    
		    $c++;
		}
		
		$this->set('info', $info);
		$this->set('id', $id);
		$this->set('data', $data);
	}

	public function send_mail($id = null){

        //get accident info
		$accident = $this->Accident->find('first', array(
            'conditions' => array(
                'Accident.id' => $id
            ),
            'contain' => array(
                'Account'=>array(
                    'fields'=>array('Account.id', 'Account.name', 'Account.abr')
                ),
                'Dept'=>array(
                    'fields'=>array('Dept.id', 'Dept.name')
                ),
                'User'=>array(
                    'fields'=>array('User.id', 'User.first_name', 'User.last_name', 'User.doh')
                ),
                'CreatedBy'=>array(),
                'ChangeBy'=>array(),
                'AccidentArea'=>array(
					'AccidentAreaLov'
				),
            ),

        ));

		$from_name = AuthComponent::user('first_name').' '. AuthComponent::user('last_name');
		$name = $accident['User']['first_name'].' '.$accident['User']['last_name'];
		$account = $accident['Account']['name'] .' ( '. $accident['Account']['abr'] .' )';
		$reported = $accident['CreatedBy']['first_name'].' '.$accident['CreatedBy']['last_name'];

		$app_id = $this->Application->get_app_id($this->request->params['controller']);
		//Get Email List
		$email_list = $this->EmailList->pickList($app_id);

		//Email Link To user
        $email = new CakeEmail();
        $email->config('smtp');
        $email->sender('support@vrifm.com', 'VRI Support');

		$email->from(array(AuthComponent::user('email') => $from_name));
        $email->template('accident', null);
        $email->to($email_list);

        $email->subject('An Accident Has Been Reported By: '. $reported );
        $email->emailFormat('html');

        #$this->set('user_email', $user_email);
        $this->set('name', $name);
        $this->set('account', $account);
        $this->set('reported', $reported);
        $this->set('id', $id);

		#$email->viewVars(array('user_email' => $user_email));
        $email->viewVars(array('name' => $name));
        $email->viewVars(array('reported' => $reported));
        $email->viewVars(array('account' => $account));
        $email->viewVars(array('id' => $id));

        $email->send();
	}
}