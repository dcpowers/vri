<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class AccountsController extends AppController {

    //public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );
    //Search Plugin

    var $uses = array(
        'User',
        'AccountDepartment',
        'Account',
        'AccountUser',
        'AssignedTest',
        'Setting',
        'Department',
        'AuthRole'
    );

    public $components = array( 'RequestHandler', 'Paginator', 'Session');

    public $presetVars = array(
        array('field' => 'q', 'type' => 'value')
    );

    public $paginate = array(
        'order' => array(
            'Account.name' => 'asc'
        ),
        'limit'=>50
    );

    public function pluginSetup() {
        $user = AuthComponent::user();

        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.Name', 'Accounts');
    }

    public function beforeFilter() {
        parent::beforeFilter();

        $this->set('title_for_layout', 'Accounts');
    }

    public function index($status=null) {
        $options = array();

        if($this->Auth->user('Role.permission_level') == 50){
            $option = array('conditions'=>array('Account.regional_admin_id' => $this->Auth->user('id')));
            $options = array_merge_recursive($options,$option);
        }

        #$this->User->virtualFields['Manager.manager_name'] = 'CONCAT(Manager.first_name, " " , Manager.last_name)';

        $this->Paginator->settings = array(
            'conditions' => array(
                #'Account.id' => $search_ids,
            ),
            'contain'=>array(
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
                'User'=>array(
                    'conditions'=>array('User.is_active' => 1),
                    'fields'=>array('User.id')
                )
            ),
            'limit' => 50,
            'order'=>array('Account.name'=> 'asc'),
        );

        if(!empty($this->request->data['Search']['q'])){
            $option = array('conditions'=>array(
                'OR'=>array(
                    'Account.name LIKE' => '%'.$this->request->data['Search']['q'].'%',
                    'Account.abr LIKE' => '%'.$this->request->data['Search']['q'].'%'
                )
            ));
            $options = array_merge_recursive($options,$option);
        }

        if(is_null($status)){
            $status = 1;
        }

        if($status == 'All'){
            $option = array('conditions'=>array('Account.is_active' => array(1,2)));
            $options = array_merge_recursive($options,$option);
            $this->set('status', 'All');
        }else{
            $option = array('conditions'=>array('Account.is_active' => $status));
            $options = array_merge_recursive($options,$option);
            $this->set('status', $status);
        }

        $this->Paginator->settings = array_merge_recursive($this->Paginator->settings,$options);

        #pr($this->Paginator->settings);
        #exit;
        #$accounts = $this->Paginator->paginate('Account');
        #pr($accounts);
        #exit;

        $this->set('accounts', $this->Paginator->paginate('Account'));

    }

    public function view($id=null, $pageStatus = null, $viewBy = null){
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
        $user_ids = $this->AccountUser->getAccountIds($id, $pageStatus);
		
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
                'Accident'=>array(
					'Dept'=>array(
						'fields'=>array('Dept.name')
					),
					'order'=>array(
						'Accident.date' => 'DESC'
					)
				),
                'AccountDepartment'=>array(
                    'Department'=>array(
                        'fields'=>array(
                            'Department.name'
                        )
                    )
                ),
                
				'Award'=>array(
					'Type'=>array(
						'fields'=>array('Type.award')
					),
					'User'=>array(
						'fields' => array('User.id', 'User.first_name', 'User.last_name'),
					),
					'Dept'=>array(
						'fields'=>array('Dept.name')
					),
					'order'=>array('Award.date' => 'DESC')
				)

            ),

        ));
		$awardList = array();

		foreach($account['Award'] as $v){
			$p = explode("-", $v['date']);

			$m = date('F', strtotime($v['date']));

			if (isset($awardList[$p[0]][$m]['count'])) {
				$awardList[$p[0]][$m]['count']++;
			}else{
				$awardList[$p[0]][$m]['count'] = 1;
			}
        }
		
		unset($account['Award']);
		$testing = $this->AssignedTest->find('all', array(
            'conditions' => array(
                'AssignedTest.user_id' => $user_ids
            ),
            'contain' => array(
                'User'=>array(
                    'fields'=>array('User.id', 'User.first_name', 'User.last_name')
                ),
                'Test'=>array(
                    'fields'=>array('Test.name'),
					'order'=>array('Test.name' => 'ASC')
                )
			),
			'order'=>array('AssignedTest.assigned_date' => 'DESC')

        ));

		$g = array();
        if(!empty($testing)){
			$c = 0;
			foreach($testing as $v){
				$index = $v['Test']['name'];

				$g[$index][$c]['User'] = $v['User']['first_name']. ' '. $v['User']['last_name'];
				$g[$index][$c]['Assigned'] = date('F d,Y', strtotime($v['AssignedTest']['assigned_date']));
				$g[$index][$c]['Completed'] = (is_null($v['AssignedTest']['completion_date'])) ? null : date('F d,Y', strtotime($v['AssignedTest']['completion_date']));
				$g[$index][$c]['Expires'] = date('F d,Y', strtotime($v['AssignedTest']['expires_date']));
				$g[$index][$c]['Complete'] = $v['AssignedTest']['complete'];

				$c++;
			}

		}
		
		$dept_ids = $this->request->data['AccountDepartment']['department_id'] = Set::extract( $account['AccountDepartment'], '/department_id' );

        $corp_emp_ids = $this->AuthRole->pickListByRole(AuthComponent::user('Role.id'));

        $userList['Vanguard Resources'] = $this->User->pickListByRole($corp_emp_ids);
        $userList[$account['Account']['name']] = $this->User->pickListByAccount($id);
        
        unset($account['Asset']);

        #pr($account);
        #exit;
		
        $corp_emp_ids = $this->AuthRole->pickListByRole(AuthComponent::user('Role.id'));
		
		$userList['Vanguard Resources'] = $this->User->pickListByRole($corp_emp_ids);
        $userList[$account['Account']['name']] = $this->User->pickListByAccount($id);
        
        $this->set('id', $id);
        $this->set('account', $account);
        $this->set('testing', $g);
        $this->set('awards', $awardList);

        $this->set('userList', $userList);
        $this->set('status', $this->Setting->pickList('status'));
        $this->set('departments', $this->Department->pickList());

        //set all active classes
        $this->set('superClass', $superClass);
        $this->set('deptClass', $deptClass);
        $this->set('roleClass', $roleClass);

        $this->set('aStatusClass', $aStatusClass);
        $this->set('iStatusClass', $iStatusClass);
        $this->set('allStatusClass', $allStatusClass);
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
                        $indexName = $data['Supervisor']['first_name'].' '. $data['Supervisor']['last_name'];
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
                            $indexName = (empty($newItem['Department']['name']) || empty($newItem['Department']['abr'])) ? 'Undefined' : $newItem['Department']['name'].' ( '. $newItem['Department']['abr'] .' )';
                            $keysort[$indexName] = (empty($newItem['Department']['name'])) ? 'Undefined' : $newItem['Department']['name'];

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
                    array('params' => array('class'=>'alert-success'))
                );
            } else {
                $this->Flash->alertBox(
                    'The Account could not be saved. Please, try again.',
                    array('params' => array('class'=>'alert-success'))
                );

                $this->set( compact( 'validationErrors' ) );

                $id = $this->request->data['Account']['id'];
            }

            $this->redirect(array('controller'=>'Accounts', 'action'=>'view', $id));
        }


    }

    public function add($id=null){
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
                foreach($this->request->data['AccountDepartment'] as $item){
                    foreach($item as $key=>$val){
                        $this->request->data['AccountDepartment'][$key]['department_id'] = $val;
                    }
                }
                unset($this->request->data['AccountDepartment']['department_id']);
            }
            
            if ($this->Account->saveAll($this->request->data)) {
                $this->Flash->alertBox(
                    'The Account: "'.$this->request->data['Account']['name'].'" has been saved',
                    array('params' => array('class'=>'alert-success'))
                );
                
                $this->redirect(array('controller'=>'Accounts', 'action'=>'view', $this->Account->id));
            } else {
                $this->Flash->alertBox(
                    'The Account could not be saved. Please, try again.',
                    array('params' => array('class'=>'alert-success'))
                );

                $this->set( compact( 'validationErrors' ) );

            }
			
			$this->request->data = $this->request->data;
			
            $this->redirect(array('controller'=>'Accounts', 'action'=>'add'));
        }

        $corp_emp_ids = $this->AuthRole->pickListByRole(AuthComponent::user('Role.id'));
        $userList['Vanguard Resources'] = $this->User->pickListByRole($corp_emp_ids);

        $this->set('userList', $userList);
        $this->set('status', $this->Setting->pickList('status'));
        $this->set('departments', $this->Department->pickList());
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
}