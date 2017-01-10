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
        'Accident',
        'AccidentCost',
        'AccidentArea',
        'AccidentFile',
		'User',
        'AccountDepartment',
        'AccountUser',
        'AccidentAreaLov'

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

		#$this->virtualFields['Account.cname'] = 'CONCAT(Account.name, "( ", Account.abr, " )")';

		$this->Paginator->settings = array(
            'conditions' => array(
                'Accident.user_id' => $user_ids,
                'Accident.is_active' => 1,
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
            'order'=>array('Accident.id'=> 'asc'),
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
            $keysort[$indexName] = $t['Account']['name'];

			unset($t['Account']);
            $value[$indexName][] = $t;

			$result = array_merge($result,$value);

		}
		if(!empty($result)){
            array_multisort($keysort, SORT_ASC, $result);
        }
        #pr($result);
		#exit;
        $this->set('accidents', $result);

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
                'AccidentCost'=>array(),
                'AccidentFile'=>array()
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
            $error = false;
            $validationErrors = array();

            $this->request->data['Account']['is_active'] = (empty($this->request->data['Account']['is_active'])) ? 1 : $this->request->data['Account']['is_active'] ;

            $this->request->data['Account']['EVS'] = (empty($this->request->data['Account']['EVS'])) ? 0 : $this->request->data['Account']['EVS'] ;
            $this->request->data['Account']['CE'] = (empty($this->request->data['Account']['CE'])) ? 0 : $this->request->data['Account']['CE'] ;
            $this->request->data['Account']['Food'] = (empty($this->request->data['Account']['Food'])) ? 0 : $this->request->data['Account']['Food'] ;
            $this->request->data['Account']['POM'] = (empty($this->request->data['Account']['POM'])) ? 0 : $this->request->data['Account']['POM'] ;
            $this->request->data['Account']['LAU'] = (empty($this->request->data['Account']['LAU'])) ? 0 : $this->request->data['Account']['LAU'] ;
            $this->request->data['Account']['SEC'] = (empty($this->request->data['Account']['SEC'])) ? 0 : $this->request->data['Account']['SEC'] ;

            if(!empty($this->request->data['AccountDepartment'])){
                $this->AccountDepartment->deleteAll(array('AccountDepartment.account_id' => $this->request->data['Account']['id']), false);

                foreach($this->request->data['AccountDepartment'] as $item){
                    foreach($item as $key=>$val){
                        $this->request->data['AccountDepartment'][$key]['account_id'] = $this->request->data['Account']['id'];
                        $this->request->data['AccountDepartment'][$key]['department_id'] = $val;
                    }
                }
                unset($this->request->data['AccountDepartment']['department_id']);
            }
            #pr($this->request->data);
            #exit;
            if ($this->Account->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'The Account: "'.$this->request->data['Account']['name'].'" has been saved',
                    array(
                        'params' => array(
                            'class'=>'alert-success'
                        )
                    )
                );
            } else {
                $this->Flash->alertBox(
                    'The Account could not be saved. Please, try again.',
                    array(
                        'params' => array(
                            'class'=>'alert-success'
                        )
                    )
                );

                $this->set( compact( 'validationErrors' ) );

                $id = $this->request->data['Account']['id'];
            }

            $this->redirect(array('controller'=>'Accounts', 'action'=>'view', $id));
        }

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

            ),

        ));

        $dept_ids = $this->request->data['AccountDepartment']['department_id'] = Set::extract( $account['AccountDepartment'], '/department_id' );

        $corp_emp_ids = $this->AuthRole->pickListByRole(AuthComponent::user('Role.id'));

        $userList['Vanguard Resources'] = $this->User->pickListByRole($corp_emp_ids);
        $userList[$account['Account']['name']] = $this->User->pickListByAccount($id);

        $this->set('account', $account);
        $this->set('account', $account);

        $this->set('userList', $userList);
        $this->set('status', $this->Setting->pickList('status'));
        $this->set('departments', $this->Department->pickList());
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
			#pr($this->request->data);
			#exit;
			if(empty($this->request->data['Accident']['description'])){
				$this->Flash->alertBox(
	            	'Please Enter A Description Of What Happened',
	                array( 'params' => array( 'class'=>'alert-danger' ))
	            );

				$this->redirect(array('controller'=>'dashboard', 'action'=>'index'));
			}

			if ($this->Accident->saveAll($this->request->data)) {
            	#Audit::log('Group record added', $this->request->data );
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

			$this->redirect(array('controller'=>'dashboard', 'action'=>'index'));
        }

        $account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

        $department_ids = $this->AccountDepartment->getDepartmentIds($account_ids);
        $this->set('userList', $this->AccountUser->pickList($account_ids));
        $this->set('areas', $this->AccidentAreaLov->pickList());
    }

    public function delete($id = null, $empDelete = null) {
        $this->Group->id = $id;
        $empDelete = (is_null($empDelete)) ? 'No' : $empDelete;

        $parent = $this->Group->getPath($this->Group->id);

        //Check for top level. If is main account/iworkzone only can delete
        $parent_count = count($parent);
        if($parent_count >= 3){
            //Grap all children ids/group id
            $allChildren = $this->Group->children($id);
            $all_ids = set::extract($allChildren, '{n}.Group.id');
            $all_ids[] = $id;

            $parent_id = $parent[1]['Group']['id'];

            if($empDelete == 'No'){
                //Grab all users in group and children, update to parent id;
                $this->GroupMembership->updateAll(
                    array('GroupMembership.group_id' => $parent_id),
                    array('GroupMembership.group_id' => $all_ids)
                );
            }else{
                //Grap all children ids/group id and delete

            }

            if($this->Group->delete()){
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
        }else{
            $this->Session->setFlash(
                __('You cannot delete this level of account'),
                'alert-box',
                array('class'=>'alert-danger')
            );

        }

        return $this->redirect(array('controller'=>'groups','action' => 'orgLayout', 'member'=>true));
    }

	public function getDashboard(){
		$links = array(
			'1'=>array(
				'title' => 'New Accident',
				'controller'=>'Accidents',
				'action'=>'add'
			),
			'2'=>array(
				'title' => 'Employee Statement',
				'controller'=>'Accidents',
				'action'=>'empStatement'
			),
			'3'=>array(
				'title' =>'Supervisor Statement',
				'controller'=>'Accidents',
				'action'=>'superStatement',
				'data-toggle'=>'modal',
				'data-target'=>'#myModal'
			)
		);

		return $links;
	}
}