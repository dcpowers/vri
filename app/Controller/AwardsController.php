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

    public function index($data = null) {
    	$pieces = explode("-", $data);
		
		$month = (isset($pieces[1]) && !empty($pieces[1])) ? $pieces[1] : date('n', strtotime('now'));
		$year = (isset($pieces[0]) && !empty($pieces[0])) ? $pieces[0] : date('Y', strtotime('now'));
		$account_ids = (isset($pieces[2]) && !empty($pieces[2])) ? array($pieces[2]) : Set::extract( AuthComponent::user(), '/AccountUser/account_id' );
        
		$dateObj   = DateTime::createFromFormat('!m', $month);
		$monthName = $dateObj->format('F'); // March
		
		$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year) - 1;
		$start = date("Y-m-d", strtotime('First day of '.$monthName.' '. $year));
		$end = date("Y-m-d", strtotime('+'. $numDays .' days', strtotime($start)));
		$currentDate = date("Y-m-d", strtotime('First day of this month'));
		$edit = ($end<$currentDate) ? true : false ;
		
		$depts = $this->Department->pickList();
        $results = array();
        ////
        $accidents = $this->Accident->find('list', array(
	    	'conditions' => array(
	        	'Accident.account_id' => $account_ids,
	        	'Accident.date >=' => $start,
	        	'Accident.date <=' => $end,
			),
	        'contain'=>array(),
            'fields'=>array('Accident.department_id')
	    ));
		$status = ($edit == 1) ? array(1,2) : 1 ;
		
		$user_ids = $this->AccountUser->getAccountIds($account_ids, 1);
		$ids = $this->DepartmentUser->removeUserIdsByDept($user_ids, $accidents);
		$users = $this->User->pickListByStartDateAndType($ids, $end, $accidents);
		////
		#pr($users);
		#exit;
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
                	'ApprovedBy'=>array(),
                	'User'=>array(),
	            ),

	        ));
	        #pr($awards);
            $results[$key]['User'] = $u['User'];
            
            if(!empty($awards)){
				
				foreach($awards as $akey=>$item){
					
					
					$results[$key]['User']['award_id'] = $item['Award']['id'];
					$results[$key]['User']['active'] = $u['User']['is_active'];
						
					if(empty($item['Award']['paid_date'])) {
						$results[$key]['User']['is_paid'] = 0;
						$results[$key]['User']['paid_date'] = null;
					} else {
						$results[$key]['User']['is_paid'] = 1;
						$results[$key]['User']['paid_date'] = date('F d, Y', strtotime($item['Award']['paid_date']));
					}

					if(empty($item['Award']['verified_date'])) {
						$results[$key]['User']['is_verified'] = 0;
						$results[$key]['User']['verified_date'] = null;
						$results[$key]['User']['verified_by'] = null;
					} else {
						$results[$key]['User']['is_verified'] = 1;
						$results[$key]['User']['verified_date'] = date('F d, Y', strtotime($item['Award']['verified_date']));
						$results[$key]['User']['verified_by'] = $item['CreatedBy']['first_name'] .' '.$item['CreatedBy']['last_name'];
					}
					
					if(empty($item['Award']['approved_date'])) {
						$results[$key]['User']['is_approved'] = 0;
						$results[$key]['User']['approved_date'] = null;
						$results[$key]['User']['approved_by'] = null;
					} else {
						$results[$key]['User']['is_approved'] = 1;
						$results[$key]['User']['approved_date'] = date('F d, Y', strtotime($item['Award']['approved_date']));
						$results[$key]['User']['approved_by'] = $item['ApprovedBy']['first_name'] .' '.$item['ApprovedBy']['last_name'];
					}
					
					$results[$key]['User']['award_amount'] = $item['Award']['amount'];
					$results[$key]['User']['award_type'] = $item['Type']['award'];
				}

			}else{
				$results[$key]['User']['is_paid'] = 0;
				$results[$key]['User']['is_verified'] = 0;
				$results[$key]['User']['is_approved'] = 0;
				
				$results[$key]['User']['award_amount'] = ($u['User']['pay_status'] == 2) ? '2.50' : '5.00';
				$results[$key]['User']['award_type'] = 'No Department Accidents';
				$results[$key]['User']['verified_date'] = null;
				$results[$key]['User']['verified_by'] = null;
				$results[$key]['User']['paid_date'] = null;
				$results[$key]['User']['award_id'] = null;
				$results[$key]['User']['approved_date'] = null;
				$results[$key]['User']['approved_by'] = null;
				$results[$key]['User']['active'] = $u['User']['is_active'];
			}
		}
		
		//get all records
		$user_ids = Set::extract($users, '{n}.User.id');
		
		$allRecords = $this->Award->find('all', array(
            'conditions' => array(
            	'NOT'=>array(
            		'Award.user_id' => $user_ids
            	),
                'Award.account_id' => $account_ids,
                'Award.date >=' => $start,
                'Award.date <=' => $end,
            ),
            'contain' => array(
            	'Type'=>array(),
            	'CreatedBy'=>array(),
            	'ApprovedBy'=>array(),
            	'User'=>array(),
            ),
        ));
        $c = 0;
       	#pr($allRecords);
       	#exit;
        if(!empty($allRecords)){
    		foreach($allRecords as $item){
    			$newResults[$c]['User'] = $item['User'];
				
				$newResults[$c]['User']['award_id'] = $item['Award']['id'];
				$newResults[$c]['User']['active'] = $item['User']['is_active'];
					
				if(empty($item['Award']['paid_date'])) {
					$newResults[$c]['User']['is_paid'] = 0;
					$newResults[$c]['User']['paid_date'] = null;
				} else {
					$newResults[$c]['User']['is_paid'] = 1;
					$newResults[$c]['User']['paid_date'] = date('F d, Y', strtotime($item['Award']['paid_date']));
				}

				if(empty($item['Award']['verified_date'])) {
					$newResults[$c]['User']['is_verified'] = 0;
					$newResults[$c]['User']['verified_date'] = null;
					$newResults[$c]['User']['verified_by'] = null;
				} else {
					$newResults[$c]['User']['is_verified'] = 1;
					$newResults[$c]['User']['verified_date'] = date('F d, Y', strtotime($item['Award']['verified_date']));
					$newResults[$c]['User']['verified_by'] = $item['CreatedBy']['first_name'] .' '.$item['CreatedBy']['last_name'];
				}
				
				if(empty($item['Award']['approved_date'])) {
					$newResults[$c]['User']['is_approved'] = 0;
					$newResults[$c]['User']['approved_date'] = null;
					$newResults[$c]['User']['approved_by'] = null;
				} else {
					$newResults[$c]['User']['is_approved'] = 1;
					$newResults[$c]['User']['approved_date'] = date('F d, Y', strtotime($item['Award']['approved_date']));
					$newResults[$c]['User']['approved_by'] = $item['ApprovedBy']['first_name'] .' '.$item['ApprovedBy']['last_name'];
				}
				
				$newResults[$c]['User']['award_amount'] = $item['Award']['amount'];
				$newResults[$c]['User']['award_type'] = $item['Type']['award'];
				
				$c++;
			}
			
			$results = array_merge($results, $newResults);
		}
		
        #pr($results);
        
        #exit;
		$editable = $edit;
		$id = $account_ids[0];
		
		if($this->request->is('ajax')){
			$id = $account_ids[0];
			$this->set(compact(
				'results', 
				'monthName', 
				'year', 
				'id', 
				'editable',
				'start',
				'end',
				'id',
				'month'
			));	
		} else {
			$this->set('id', $account_ids[0]);
        	$this->set('monthName', $monthName);
        	$this->set('month', $month);
        	$this->set('year', $year);
        	$this->set('results', $results);
        	$this->set('start', $start);
        	$this->set('end', $end);
		
			$this->set('editable', $editable);
		}
	}
	
	public function getMenu($acct_id = null){
		return $this->Award->historyMenu($acct_id);
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
		$month = (!empty($this->request->data['Awards']['month'])) ? $this->request->data['Awards']['month'] : date('n', strtotime('-1 month'));
        $year = (!empty($this->request->data['Awards']['year'])) ? $this->request->data['Awards']['year'] : date('Y', strtotime('now'));

		$dateObj   = DateTime::createFromFormat('!m', $month);
		$monthName = $dateObj->format('F'); // March

		$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year) - 1;
		$start = date("Y-m-d", strtotime('First day of '.$monthName.' '. $year));
		$end = date("Y-m-d", strtotime('+'. $numDays .' days', strtotime($start)));
		
		$currentDate = date("Y-m-d", strtotime('First day of this month'));
		$edit = ($end<$currentDate) ? true : false ;
		
		for($i=1; $i<=12; $i++){
			$months[$i] = date( 'F', mktime( 0, 0, 0, $i + 1, 0, 0, 0 ) );
		}

		$current_year = date('Y', strtotime('now'));

		for($y=2013; $y<=$current_year; $y++){
			$years[$y] = $y;
		}

        $this->set('month', $month);
        $this->set('months', $months);
        $this->set('end', $end);
        $this->set('year', $year);
        $this->set('years', $years);
        
        $accidents = $this->Accident->find('list', array(
		    'conditions' => array(
		        'Accident.date >=' => $start,
		        'Accident.date <=' => $end,
			),
		    'contain'=>array(),
	        'fields'=>array('Accident.department_id', 'Accident.account_id')
		));
		
        $this->set('results', $this->User->pickListByPayType($start, $end, $accidents));
        $this->set('editable', $edit);
    }
    
    public function reportView($data = null) {
    	$pieces = explode("-", $data);
		
		$month = $pieces[1];
		$year = $pieces[0];
		$account_ids = $pieces[2];
        $acct_name = $this->Account->getAcctName($pieces[2]);
        $acct_name = $acct_name[$pieces[2]];
        
		$dateObj   = DateTime::createFromFormat('!m', $month);
		$monthName = $dateObj->format('F'); // March
		
		$numDays = cal_days_in_month(CAL_GREGORIAN, $month, $year) - 1;
		$start = date("Y-m-d", strtotime('First day of '.$monthName.' '. $year));
		$end = date("Y-m-d", strtotime('+'. $numDays .' days', strtotime($start)));
		$currentDate = date("Y-m-d", strtotime('First day of this month'));
		$edit = ($end<$currentDate) ? true : false ;
		
		$depts = $this->Department->pickList();
        $results = array();
        ////
        $accidents = $this->Accident->find('list', array(
	    	'conditions' => array(
	        	'Accident.account_id' => $account_ids,
	        	'Accident.date >=' => $start,
	        	'Accident.date <=' => $end,
			),
	        'contain'=>array(),
            'fields'=>array('Accident.department_id')
	    ));
		$status = ($edit == 1) ? array(1,2) : 1 ;
		
		$user_ids = $this->AccountUser->getAccountIds($account_ids, 1);
		$ids = $this->DepartmentUser->removeUserIdsByDept($user_ids, $accidents);
		$users = $this->User->pickListByStartDateAndType($ids, $end, $accidents);
		////
		#pr($users);
		#exit;
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
                	'ApprovedBy'=>array(),
                	'User'=>array(),
	            ),

	        ));
	        #pr($awards);
            $results[$key]['User'] = $u['User'];
            
            if(!empty($awards)){
				
				foreach($awards as $akey=>$item){
					
					
					$results[$key]['User']['award_id'] = $item['Award']['id'];
					$results[$key]['User']['active'] = $u['User']['is_active'];
						
					if(empty($item['Award']['paid_date'])) {
						$results[$key]['User']['is_paid'] = 0;
						$results[$key]['User']['paid_date'] = null;
					} else {
						$results[$key]['User']['is_paid'] = 1;
						$results[$key]['User']['paid_date'] = date('F d, Y', strtotime($item['Award']['paid_date']));
					}

					if(empty($item['Award']['verified_date'])) {
						$results[$key]['User']['is_verified'] = 0;
						$results[$key]['User']['verified_date'] = null;
						$results[$key]['User']['verified_by'] = null;
					} else {
						$results[$key]['User']['is_verified'] = 1;
						$results[$key]['User']['verified_date'] = date('F d, Y', strtotime($item['Award']['verified_date']));
						$results[$key]['User']['verified_by'] = $item['CreatedBy']['first_name'] .' '.$item['CreatedBy']['last_name'];
					}
					
					if(empty($item['Award']['approved_date'])) {
						$results[$key]['User']['is_approved'] = 0;
						$results[$key]['User']['approved_date'] = null;
						$results[$key]['User']['approved_by'] = null;
					} else {
						$results[$key]['User']['is_approved'] = 1;
						$results[$key]['User']['approved_date'] = date('F d, Y', strtotime($item['Award']['approved_date']));
						$results[$key]['User']['approved_by'] = $item['ApprovedBy']['first_name'] .' '.$item['ApprovedBy']['last_name'];
					}
					
					$results[$key]['User']['award_amount'] = $item['Award']['amount'];
					$results[$key]['User']['award_type'] = $item['Type']['award'];
				}

			}else{
				$results[$key]['User']['is_paid'] = 0;
				$results[$key]['User']['is_verified'] = 0;
				$results[$key]['User']['is_approved'] = 0;
				
				$results[$key]['User']['award_amount'] = ($u['User']['pay_status'] == 2) ? '2.50' : '5.00';
				$results[$key]['User']['award_type'] = 'No Department Accidents';
				$results[$key]['User']['verified_date'] = null;
				$results[$key]['User']['verified_by'] = null;
				$results[$key]['User']['paid_date'] = null;
				$results[$key]['User']['award_id'] = null;
				$results[$key]['User']['approved_date'] = null;
				$results[$key]['User']['approved_by'] = null;
				$results[$key]['User']['active'] = $u['User']['is_active'];
			}
		}
		
		//get all records
		$user_ids = Set::extract($users, '{n}.User.id');
		
		$allRecords = $this->Award->find('all', array(
            'conditions' => array(
            	'NOT'=>array(
            		'Award.user_id' => $user_ids
            	),
                'Award.account_id' => $account_ids,
                'Award.date >=' => $start,
                'Award.date <=' => $end,
            ),
            'contain' => array(
            	'Type'=>array(),
            	'CreatedBy'=>array(),
            	'ApprovedBy'=>array(),
            	'User'=>array(),
            ),
        ));
        $c = 0;
       	#pr($allRecords);
       	#exit;
        if(!empty($allRecords)){
    		foreach($allRecords as $item){
    			$newResults[$c]['User'] = $item['User'];
				
				$newResults[$c]['User']['award_id'] = $item['Award']['id'];
				$newResults[$c]['User']['active'] = $item['User']['is_active'];
					
				if(empty($item['Award']['paid_date'])) {
					$newResults[$c]['User']['is_paid'] = 0;
					$newResults[$c]['User']['paid_date'] = null;
				} else {
					$newResults[$c]['User']['is_paid'] = 1;
					$newResults[$c]['User']['paid_date'] = date('F d, Y', strtotime($item['Award']['paid_date']));
				}

				if(empty($item['Award']['verified_date'])) {
					$newResults[$c]['User']['is_verified'] = 0;
					$newResults[$c]['User']['verified_date'] = null;
					$newResults[$c]['User']['verified_by'] = null;
				} else {
					$newResults[$c]['User']['is_verified'] = 1;
					$newResults[$c]['User']['verified_date'] = date('F d, Y', strtotime($item['Award']['verified_date']));
					$newResults[$c]['User']['verified_by'] = $item['CreatedBy']['first_name'] .' '.$item['CreatedBy']['last_name'];
				}
				
				if(empty($item['Award']['approved_date'])) {
					$newResults[$c]['User']['is_approved'] = 0;
					$newResults[$c]['User']['approved_date'] = null;
					$newResults[$c]['User']['approved_by'] = null;
				} else {
					$newResults[$c]['User']['is_approved'] = 1;
					$newResults[$c]['User']['approved_date'] = date('F d, Y', strtotime($item['Award']['approved_date']));
					$newResults[$c]['User']['approved_by'] = $item['ApprovedBy']['first_name'] .' '.$item['ApprovedBy']['last_name'];
				}
				
				$newResults[$c]['User']['award_amount'] = $item['Award']['amount'];
				$newResults[$c]['User']['award_type'] = $item['Type']['award'];
				
				$c++;
			}
			
			$results = array_merge($results, $newResults);
		}
		
        #pr($results);
        
        #exit;
		$editable = $edit;
		$id = $account_ids[0];
		
		if($this->request->is('ajax')){
			$id = $account_ids[0];
			$this->set(compact(
				'results', 
				'monthName', 
				'year', 
				'id', 
				'editable',
				'start',
				'end',
				'id',
				'month',
				'acct_name'
			));	
		} else {
			$this->set('id', $account_ids[0]);
        	$this->set('monthName', $monthName);
        	$this->set('month', $month);
        	$this->set('year', $year);
        	$this->set('results', $results);
        	$this->set('start', $start);
        	$this->set('end', $end);
        	$this->set('acct_name', $acct_name);
		
			$this->set('editable', $editable);
		}
	}
	
    public function process($dateData=null){
        if ($this->request->is('post') || $this->request->is('put')) {
        	if(!empty($this->request->data)){
				foreach($this->request->data['Awards'] as $v){
					if(isset($v['verify']) && $v['verify'] == 1 ){
						unset($v['verify']);
						$data['Award'] = $v;
						
						if(!isset($v['id'])){
							$this->Award->create();
						}
						
						$this->Award->save($data);
					}
				}
				
				$this->Flash->alertBox(
            		'Awards Have Been Verified',
	                array('params' => array('class'=>'alert-success'))
				);
			}
			
			$this->redirect(array('controller'=>'Awards', 'action'=>'index', $dateData));
        }
    }
    
    public function processReport($dateData=null){
        if ($this->request->is('post') || $this->request->is('put')) {
        	
        	if(!empty($this->request->data)){
				foreach($this->request->data['Awards'] as $v){
					if(isset($v['verify']) && $v['verify'] == 1 ){
						unset($v['verify']);
						$data['Award'] = $v;
						
						if(!isset($v['id'])){
							$this->Award->create();
						}
						#pr($data);
						#exit;
						$this->Award->save($data);
					}
				}
				
				$this->Flash->alertBox(
            		'Awards Have Been Approved',
	                array('params' => array('class'=>'alert-success'))
				);
			}
			
			$this->redirect(array('controller'=>'Awards', 'action'=>'report'));
			
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

	public function verify($month = null, $year = null){
    	$month = (!is_null($month)) ? $month : date('n', strtotime('now'));
        $year = (!is_null($year)) ? $year : date('Y', strtotime('now'));
        #pr($month);
		#exit;
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