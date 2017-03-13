<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class AwardsController extends AppController {

    //public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );
    //Search Plugin

    var $uses = array(
        'Award',
		'AccountUser',
		'User',
		'Department'
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
        Configure::write('App.Name', 'Awards');
    }

    public function beforeFilter() {
        parent::beforeFilter();

        $this->set('title_for_layout', 'Awards');
    }

    public function index() {

		$month = (!empty($this->request->data['Awards']['month'])) ? $this->request->data['Awards']['month'] : date('n', strtotime('now'));
        $year = (!empty($this->request->data['Awards']['year'])) ? $this->request->data['Awards']['year'] : date('Y', strtotime('now'));

		$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
		$user_ids = $this->AccountUser->getAccountIds($account_ids);
		$users = $this->User->pickListByStartDate($user_ids, $month, $year);
        $depts = $this->Department->pickList();

        foreach($users as $key=>$u){
			$users[$key]['Awards'] = $this->Award->find('all', array(
	            'conditions' => array(
	                'Award.user_id' => $u['User']['id'],
	                'month(Award.date)' => $month,
	                'year(Award.date)' => $year,
	            ),
	            'contain' => array(
                	'Type'=>array(),
                	'CreatedBy'=>array(),
	            ),

	        ));

			#pr($users[$key]['Awards']);
		}

        #pr($depts);
		#pr($users);
		#exit;
		$result = array();
		$c = 0;

		for($i=1; $i<=12; $i++){
			$months[$i] = date( 'F', mktime( 0, 0, 0, $i + 1, 0, 0, 0 ) );
		}

		$current_year = date('Y', strtotime('now'));

		for($y=2013; $y<=$current_year; $y++){
			$years[$y] = $y;
		}

        $this->set('month', $month);
        $this->set('months', $months);
        $this->set('year', $year);
        $this->set('years', $years);
		$this->set('results', $users);

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

				$this->redirect(array('controller'=>'dashboard', 'action'=>'index'));
			}

			if ($this->Accident->saveAll($this->request->data)) {
            	#Audit::log('Group record added', $this->request->data );

				$now = date('Y-m-d', strtotime('now'));
                $this->BingoGame->updateAll(
				    array( 'BingoGame.end_date' => "' $now '", 'BingoGame.amount' => 0 ),   //fields to update
				    array('BingoGame.end_date' => null, 'BingoGame.account_id' =>  AuthComponent::user('AccountUser.0.account_id'))  //condition
				);
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
}