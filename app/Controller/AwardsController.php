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
		'Account',
		'Department',
		'DepartmentUser',
		'Accident'
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

		$dateObj   = DateTime::createFromFormat('!m', $month);
		$monthName = $dateObj->format('F'); // March

		$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year) - 1;
		$start = date("Y-m-d", strtotime('First day of '.$monthName.' '. $year));
		$end = date("Y-m-d", strtotime('+'. $numDays .' days', strtotime($start)));

		$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
		$user_ids = $this->AccountUser->getAccountIds($account_ids, 1);
		$users = $this->User->pickListByStartDate($user_ids, $end);

        $depts = $this->Department->pickList();
        $results = array();

		foreach($users as $key=>$u){
			$awards = $this->Award->find('all', array(
	            'conditions' => array(
	                'Award.user_id' => $u['User']['id'],
	                'Award.date >=' => $start,
	                'Award.date <=' => $end,
	            ),
	            'contain' => array(
                	'Type'=>array(),
                	'CreatedBy'=>array(),
	            ),

	        ));

			if(!empty($awards)){
				$results[$key]['User'] = $u['User'];
				$results[$key]['User']['is_paid'] = 1;
				$results[$key]['User']['is_verified'] = 1;
				foreach($awards as $akey=>$item){
					if(empty($item['Award']['paid_date'])){
						$results[$key]['User']['is_paid'] = 0;
					}

					if(empty($item['Award']['verified_date'])){
						$results[$key]['User']['is_verified'] = 0;
					}

					$results[$key]['Awards'][] = $item;
				}

			}
		}

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
		$this->set('results', $results);

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

	public function report(){
		$month = (!empty($this->request->data['Awards']['month'])) ? $this->request->data['Awards']['month'] : date('n', strtotime('now'));
        $year = (!empty($this->request->data['Awards']['year'])) ? $this->request->data['Awards']['year'] : date('Y', strtotime('now'));

		$dateObj   = DateTime::createFromFormat('!m', $month);
		$monthName = $dateObj->format('F'); // March

		$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year) - 1;
		$start = date("Y-m-d", strtotime('First day of '.$monthName.' '. $year));
		$end = date("Y-m-d", strtotime('+'. $numDays .' days', strtotime($start)));

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
        $acct_ids = $this->Account->fullListActive();

		foreach($acct_ids as $id=>$name){
			$awards[$name] = $this->Award->find('all', array(
	    		'conditions' => array(
		            'Award.account_id >=' => $id,
		            'Award.date >=' => $start,
		            'Award.date <=' => $end,
		        ),
		        'contain' => array(
            		'Type'=>array(),
	                'CreatedBy'=>array(),
					'User'=>array(
						'fields'=>array(
							'User.first_name',
							'User.last_name',
						)
					),
					'Account'=>array()
		        ),
	        ));
		}
        $c=0;
		if(!empty($awards)){
			foreach($awards as $comp_name=>$item){
				$key = $comp_name;

				if(!empty($item)){
					foreach($item as $v){
						$results[$key][$c]['award']['monthYear'] = date('F Y', strtotime($v['Award']['date']));
						$results[$key][$c]['award']['ver_by'] = $v['CreatedBy']['first_name'] .' '.$v['CreatedBy']['last_name'];
						$results[$key][$c]['award']['ver_date'] = date('F d, Y', strtotime($v['Award']['verified_date']));
						$results[$key][$c]['award']['user'] = $v['User']['first_name'] .' '.$v['User']['last_name'];
						$results[$key][$c]['award']['type'] = $v['Type']['award'];
						$results[$key][$c]['award']['amount'] = $v['Award']['amount'];

						$c++;
					}
				}else{
					$results[$key][]['award']['error'] = 'No Records Found';
				}
				#$results[$key][]['info'][''] = $item;
				#$results[$key][]['info'][''] = $item;
				#$results[$key][]['info'][''] = $item;
            }
        }
		$this->set('results', $results);
    }

    public function process($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {

			if(!empty($this->request->data)){
				foreach($this->request->data['Awards'] as $v){
					if($v['verify'] == 1 AND $v['amount'] >= 1){
						unset($v['verify']);
						$data['Award'] = $v;
						$this->Award->create();
						$this->Award->save($v);
					}
				}

				$this->Flash->alertBox(
            		'Awards Have Been Verified',
	                array('params' => array('class'=>'alert-success'))
				);
			}

			$this->redirect(array('controller'=>'Awards', 'action'=>'index'));
        }
    }

    public function paid($id=null){
        $this->Award->id = $id;
        if (!$this->Award->exists()) {
            throw new NotFoundException(__('Invalid Award Id'));
        }
		$this->request->data['Award']['id'] = $id;
		$this->request->data['Award']['paid_date'] = date('Y-m-d',strtotime('now'));

		if ($this->Award->save($this->request->data)) {
			$date = date('F d, Y',strtotime('now'));

        }else{
        	$date = null;
        }

		$this->set(compact('date'));


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

	public function verify(){
    	$month = (!empty($this->request->data['Awards']['month'])) ? $this->request->data['Awards']['month'] : date('n', strtotime('now'));
        $year = (!empty($this->request->data['Awards']['year'])) ? $this->request->data['Awards']['year'] : date('Y', strtotime('now'));

		$dateObj   = DateTime::createFromFormat('!m', $month);
		$monthName = $dateObj->format('F'); // March

		$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year) - 1;
		$start = date("Y-m-d", strtotime('First day of '.$monthName.' '. $year));
		$end = date("Y-m-d", strtotime('+'. $numDays .' days', strtotime($start)));

		$account_ids = Set::extract( AuthComponent::user(), '/AccountUser/account_id' );

		//get any accidents
        $accidents = $this->Accident->find('list', array(
	    	'conditions' => array(
	        	'Accident.account_id' => $account_ids,
	        	'Accident.date >=' => $start,
	        	'Accident.date <=' => $end,
			),
	        'contain'=>array(),
            'fields'=>array('Accident.department_id')
	    ));
        $user_ids = $this->AccountUser->getAccountIds($account_ids, 1);
		$ids = $this->DepartmentUser->removeUserIdsByDept($user_ids, $accidents);
		$users = $this->User->pickListByStartDateAndType($ids, $end, $accidents);
        $depts = $this->Department->pickList();
        #pr($user_ids);
		#pr($ids);
		#pr($users);
		$results = array();
        #pr($users);
		#exit;
		foreach($users as $key=>$u){
			$count = $this->Award->find('count', array(
	            'conditions' => array(
	                'Award.user_id' => $u['User']['id'],
	                'Award.date >=' => $start,
	                'Award.date <=' => $end,
	                'Award.award_type_id' => 1,
	            ),
	            'contain' => array(
                	'Type'=>array(),
                	'CreatedBy'=>array(),
	            ),

	        ));

			if($count == 0){
				$results[] = $u;
			}
        }

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
		$this->set('results', $results);
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