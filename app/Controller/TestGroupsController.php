<?php

App::uses('AppController', 'Controller');

class TestGroupsController extends AppController {
	public $components = array('Search.Prg', 'RequestHandler', 'Paginator');

    public $uses = array(
        'TestGroup',
        'TestCategoryType',
        'Test',
        'Report.ReportSwitch',
        'AssignedTest'
    );

    function beforeFilter() {
        parent::beforeFilter();

        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
        $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );
        $this->group_id = (!empty($supervisorOf_id)) ? $supervisorOf_id : array(AuthComponent::user('parent_group_ids.1')) ;

    }

    public function add() {

        if ($this->request->is('post') || $this->request->is('put')) {
            $error = false;

            if(empty($this->request->data['TestGroup']['name'])){
                $error = true;
            }
        	if($error == false){
                $this->request->data['TestGroup']['category_type'] = 1;
                $this->request->data['TestGroup']['account_id'] = AuthComponent::user('AccountUser.0.account_id');

                $this->TestGroup->create();
                if ($this->TestGroup->saveAll($this->request->data)) {
                    $id = $this->TestGroup->id;

                    switch($this->request->data['TestGroup']['schedule_type']){
                        case 'Single':
                            $data['ReportSwitch']['report_id'] = 455;
                            break;

                        case 'MultiplePeople':
                            $data['ReportSwitch']['report_id'] = 457;
                            break;

                        case 'Group':
                        case 'Blind':
                            $data['ReportSwitch']['report_id'] = 456;
                            break;
                    }

                    $data['ReportSwitch']['test_id'] = $id;
                    $data['ReportSwitch']['group_id'] = 2;

                    $this->ReportSwitch->create();
                    $this->ReportSwitch->saveAll($data);

					$this->Flash->alertBox(
	                    'The Test: "'.$this->request->data['TestGroup']['name'].'" has been saved', [
	                        'key' => 'profile',
	                        'params' => [ 'class'=>'alert-success' ]
	                    ]
	                );
				} else {
                    $this->Flash->alertBox(
	                    'The Test could not be saved. Please, try again.', [
	                        'key' => 'profile',
	                        'params' => [ 'class'=>'alert-danger' ]
	                    ]
	                );

                }
            }else{
                $this->Session->setFlash(__('Name Cannot be empty. Please, try again.'), 'alert-box', array('class'=>'alert-danger'));
            }

            $this->redirect(array('controller'=>'TestGroups', 'action'=>'index'));
        }

        $settings['scheduleType'] = $this->TestGroup->scheduleTypeInt();
        $settings['options'] = $this->TestGroup->statusInt();

        $this->set( 'settings', $settings );

    }

    public function addSub($parent_id=null, $test_id=null, $cat_type=null) {

        if ($this->request->is('post') || $this->request->is('put')) {
            $error = false;

            $test_id = $this->request->data['TestGroup']['test_id'];

            if(empty($this->request->data['TestGroup']['name']) && $this->request->data['TestGroup']['category_type'] != 4){
                $error = true;
            }

            if($this->request->data['TestGroup']['category_type'] == 4){
                unset($this->request->data['TestGroup']);

                foreach($this->request->data as $key=>$item){
                    if(empty($item['TestGroup']['name'])){
                        unset($this->request->data[$key]);
                    }
                }
            }

            if($error == false && !empty($this->request->data)){
                $this->TestGroup->create();
                if ($this->TestGroup->saveall($this->request->data)) {
                    $this->Session->setFlash(__('Save Successful'), 'alert-box', array('class'=>'alert-success'));
                } else {
                    $this->Session->setFlash(__('Error. Please, try again.'), 'alert-box', array('class'=>'alert-danger'));
                }
            }else{
                $this->Session->setFlash(__('Name Cannot be empty. Please, try again.'), 'alert-box', array('class'=>'alert-danger'));
            }

            $this->redirect(array('controller'=>'TestGroups', 'action'=>'index', $test_id, 'member'=>true));
        }

        $this->set( 'parent_id', $parent_id );
        $this->set( 'test_id', $test_id );
        $this->set( 'category_type', $cat_type );

    }

    public function movedown($id=null, $test_id = null, $delta = 1) {
        $this->TestGroup->id = $id;
        if (!$this->TestGroup->exists()) {
           throw new NotFoundException(__('Invalid Id'));
        }

        if ($delta > 0) {
            $this->TestGroup->moveDown($this->TestGroup->id, abs($delta));
        } else {
            $this->Session->setFlash(
              'Please provide the number of positions the field should be' .
              'moved down.'
            );
        }

        return $this->redirect(array('controller'=>'TestGroups', 'action'=>'index', $test_id, 'member'=>true));

    }

    public function moveup($id=null, $test_id = null, $delta = 1) {
        $this->TestGroup->id = $id;
        if (!$this->TestGroup->exists()) {
           throw new NotFoundException(__('Invalid Id'));
        }

        if ($delta > 0) {
            $this->TestGroup->moveUp($this->TestGroup->id, abs($delta));
        } else {
            $this->Session->setFlash(
              'Please provide the number of positions the field should be' .
              'moved down.'
            );
        }

        return $this->redirect(array('controller'=>'TestGroups', 'action'=>'index', $test_id, 'member'=>true));

    }

    public function delete($id=null, $test_id=null) {
        $this->TestGroup->id = $id;
        if (!$this->TestGroup->exists()) {
           throw new NotFoundException(__('Invalid Id'));
        }

		if($this->TestGroup->delete($this->TestGroup->id)){
            //Delete Assinged Testing
            $this->AssignedTest->deleteAll(array('AssignedTest.test_id' => $id), false);
            //Delete Report Switch
            $this->ReportSwitch->deleteAll(array('ReportSwitch.test_id' => $id), false);

			$this->Flash->alertBox(
	        	'Deletion Successful', [
					'key' => 'profile',
	            	'params' => [ 'class'=>'alert-success' ]
	        	]
	        );

        }else{
			$this->Flash->alertBox(
	        	'Error. Please, try again.', [
					'key' => 'profile',
	            	'params' => [ 'class'=>'alert-success' ]
	        	]
	        );

        }

        return $this->redirect(array('controller'=>'TestGroups', 'action'=>'index', 'member'=>true));

    }

    public function inline_edit($id=null) {

        if ($this->request->is('post')) {

            $field = $this->request->data['TestGroup']['field'];
            $id = $this->request->data['TestGroup']['id'];
            $value = $this->request->data['TestGroup']['value'];

            $this->request->data['TestGroup']['id'] = $id;
            $this->request->data['TestGroup'][$field] = $value;

            if ($this->TestGroup->save($this->request->data['TestGroup'])) {
                echo json_encode( array('success'=>true ) );
                exit;
            } else {
                echo json_encode( array('success'=>false ) );
                exit;
            }
        }
        $this->layout = 'blank_nojs';
    }

    public function dashboard(){
        $supervisorOf_id = Set::extract( AuthComponent::user(), '/SupervisorOf/id' );
        $role_ids = Set::extract( AuthComponent::user(), '/AuthRole/id' );

        if(!empty($supervisorOf_id) || in_array(4,$role_ids)){
            //get children ids of the super id
            $group_id = (!empty($supervisorOf_id)) ? $supervisorOf_id : array(AuthComponent::user('parent_group_ids.1')) ;

            $tests = $this->TestGroup->fullList( $group_id );

            $settings['options'] = $this->TestGroup->statusInt();
            $settings['scheduleType'] = $this->TestGroup->scheduleTypeInt();

            return array($tests,$settings);

        }else{
            echo 'You do not have permission to view this';
        }
    }

	public function index($id=null) {
		if(is_null($id)){
			if(AuthComponent::user('Role.permission_level') >= 60){
	            $tests = $this->TestGroup->fullList();

				$this->set( 'tests', $this->TestGroup->fullList());
				//Show all tests
			}

			if(AuthComponent::user('Role.permission_level') == 30 || AuthComponent::user('Role.permission_level') == 40 ){
				$tests = $this->TestGroup->fullList( array('conditions'=>array('TestGroup.account_id' =>AuthComponent::user('AccountUser.0.account_id') )));

				$this->set( 'tests', $this->TestGroup->fullList( array('conditions'=>array('TestGroup.account_id' =>AuthComponent::user('AccountUser.0.account_id') )) ));
				//Show Account only
			}

			if(AuthComponent::user('Role.permission_level') == 50 ){
				$group_ids = $this->Account->find('first', array(
		            'conditions' => array(
		                'Account.regional_admin_id' =>AuthComponent::user('id')
		            ),
		            'contain'=>array(

		            ),

		        ));

				$tests = $this->TestGroup->fullList( array('conditions'=>array('TestGroup.account_id' =>$group_ids )));

	            $this->set( 'tests', $this->TestGroup->fullList( array('conditions'=>array('TestGroup.account_id' =>$group_ids )) ));
				//Show regional admin account only only
			}

            $settings['scheduleType'] = $this->TestGroup->scheduleTypeInt();
            $settings['options'] = $this->TestGroup->statusInt();

			$this->set( 'settings', $settings );

	        $this->render('_test_form');

	        $this->set('breadcrumbs', array(
        		array('title'=>'Testing', 'link'=>array('controller'=>'tests', 'action'=>'index', 'member'=>true ) ),
	        ));
	        return;
		}

        set_time_limit(60*60*24);
        $parent = $this->TestGroup->find('first', array(
			'conditions' => array(
				'TestGroup.id' => $id
			)
		));

		$data = $this->TestGroup->find('threaded', array(
            'conditions' => array(
                'TestGroup.lft >=' => $parent['TestGroup']['lft'],
                'TestGroup.rght <=' => $parent['TestGroup']['rght']
            ),
            'contain'=>array(
            ),
            'order'=>array('TestGroup.lft')
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

   	public function details($id=null) {
        //$this->request->onlyAllow('ajax'); // No direct access via browser URL - Note for Cake2.5: allowMethod()
        $this->autoRender = false;
        $id = $this->request->query('id');
        if (!$id) {
            throw new NotFoundException();
        }
        $data = $this->TestGroup->find('first', array(
            'conditions' => array(
                'TestGroup.id' => $id
            ),
            'contain'=>array(
            ),
        ));

        $parents = $this->TestGroup->getPath($id);

        $data['TestGroup']['Test_id'] = $parents[0]['TestGroup']['id'];
        echo json_encode($data);
        exit;
        //$this->set(compact('data')); // Pass $data to the view
        //$this->set('_serialize', 'data'); // Let the JsonView class know what variable to use

    }

    public function update() {
       if ($this->request->is('post')) {
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

    public function view($id=null) {
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
}
?>