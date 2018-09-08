<?php
    // app/Controller/UsersController.php
    App::uses('CakeEmail', 'Network/Email');
    App::uses('AppController', 'Controller');

class SettingsController extends AppController {
    public $uses = array(
        'AuthRole',
        'TrainingCategory',
        'Department'
    );

    public $components = array( 'RequestHandler', 'Paginator', 'Session');

    public function pluginSetup() {
        $user = AuthComponent::user();

        //These Two Lines are Required
        parent::pluginSetup();
        Configure::write('App.Name', 'Site Settings');
    }

    public function beforeFilter() {
        parent::beforeFilter();

        $this->set('title_for_layout', 'Site Settings');
    }

    

    public function index() {
        
        #$this->set('title', $title);
    }
    
    public function view($value = null) {
    	$saveType = $value;
    	
    	switch($value){
			case 'types':
				
				break;
			
			case 'permissions':
				break;
			
			case 'categories':
				$title = 'Training Categories';
				$index = 'TrainingCategory';
				$data = $this->TrainingCategory->pickListAll();
				break;
			
			case 'departments':
				$title = 'Departments';
				$index = 'Department';
				$data = $this->Department->pickListAll();
				break;
			
			default:
				break;
		}
    	
    	$this->set('records', $data);
    	$this->set('title', $title);
    	$this->set('index', $index);
    	$this->set('saveType', $saveType);
    }

    public function add($value=null) {
        if ($this->request->is('post')) {
        	switch($value){
				case 'types':
					break;
				
				case 'permissions':
					break;
				
				case 'categories':
					if($this->TrainingCategory->save($this->request->data)){
		                $this->Flash->alertBox(
		                    'Add Successful',
		                    array('params' => array('class'=>'alert-success'))
		                );
		            } else {
		            	$this->Flash->alertBox(
		                    'There Was An Error! Please Try Again',
		                    array('params' => array('class'=>'alert-danger'))
		                );
		            }
		            
					$title = 'Training Categories';
					$index = 'TrainingCategory';
					$data = $this->TrainingCategory->pickListAll();
					break;
				
				case 'departments':
					if($this->Department->save($this->request->data)){
		                $this->Flash->alertBox(
		                    'Add Successful',
		                    array('params' => array('class'=>'alert-success'))
		                );
		            } else {
		            	$this->Flash->alertBox(
		                    'There Was An Error! Please Try Again',
		                    array('params' => array('class'=>'alert-danger'))
		                );
		            }
	            
					$title = 'Departments';
					$index = 'Department';
					$data = $this->Department->getRecord($id);
					break;
				
				default:
					break;
			}
			
			$this->set('records', $data);
    		$this->set('title', $title);
    		$this->set('index', $index);
    		$this->set('saveType', $value);
    	
			return $this->redirect(array('controller'=>'Settings','action' => 'view', $value));    
        }
		
		switch($value){
			case 'types':
				break;
			
			case 'permissions':
				break;
			
			case 'categories':
				$title = 'Training Categories';
				$index = 'TrainingCategory';
				break;
			
			case 'departments':
				$title = 'Departments';
				$index = 'Department';
				break;
			
			default:
				break;
		}
		
		$this->set('title', $title);
    	$this->set('index', $index);
    	$this->set('saveType', $value);
       


    }

    public function edit($value = null, $id = null) {
    	if ($this->request->is('post') || $this->request->is('put')) {
    		switch($value){
				case 'types':
					
					break;
				
				case 'permissions':
					break;
				
				case 'categories':
					$this->TrainingCategory->id = $id;
					
					if($this->TrainingCategory->save($this->request->data)){
		                $this->Flash->alertBox(
		                    'Edit Successful',
		                    array('params' => array('class'=>'alert-success'))
		                );
		            } else {
		            	$this->Flash->alertBox(
		                    'There Was An Error! Please Try Again',
		                    array('params' => array('class'=>'alert-danger'))
		                );
		            }
		            
					$title = 'Training Categories';
					$index = 'TrainingCategory';
					$data = $this->TrainingCategory->pickListAll();
					break;
				
				case 'departments':
					$this->Department->id = $id;
					
					if($this->Department->save($this->request->data)){
		                $this->Flash->alertBox(
		                    'Edit Successful',
		                    array('params' => array('class'=>'alert-success'))
		                );
		            } else {
		            	$this->Flash->alertBox(
		                    'There Was An Error! Please Try Again',
		                    array('params' => array('class'=>'alert-danger'))
		                );
		            }
	            
					$title = 'Departments';
					$index = 'Department';
					$data = $this->Department->getRecord($id);
					break;
				
				default:
					break;
			}
			
			$this->set('records', $data);
    		$this->set('title', $title);
    		$this->set('index', $index);
    		$this->set('saveType', $value);
    	
			return $this->redirect(array('controller'=>'Settings','action' => 'view', $value));
		}
		
        switch($value){
			case 'types':
				
				break;
			
			case 'permissions':
				break;
			
			case 'categories':
				$this->TrainingCategory->id = $id;
				$title = 'Training Categories';
				$index = 'TrainingCategory';
				$data = $this->TrainingCategory->getRecord($id);
				break;
			
			case 'departments':
				$this->Department->id = $id;
				
				$title = 'Departments';
				$index = 'Department';
				$data = $this->Department->getRecord($id);
				break;
			
			default:
				break;
		}
		$this->set('records', $data);
    	$this->set('title', $title);
    	$this->set('index', $index);
    	$this->set('saveType', $value);

    }

    public function delete($value = null, $id = null) {
        switch($value){
			case 'types':
				break;
			
			case 'permissions':
				break;
			
			case 'categories':
				$this->TrainingCategory->id = $id;
				if($this->TrainingCategory->delete()){
	                $this->Flash->alertBox(
	                    'Deletion Successful',
	                    array('params' => array('class'=>'alert-success'))
	                );
	            } else {
	            	$this->Flash->alertBox(
	                    'There Was An Error! Please Try Again',
	                    array('params' => array('class'=>'alert-danger'))
	                );
	            }
				$title = 'Training Categories';
				$index = 'TrainingCategory';
				$data = $this->TrainingCategory->pickListAll();
				break;
			
			case 'departments':
				$this->Department->id = $id;
				if($this->Department->delete()){
	                $this->Flash->alertBox(
	                    'Deletion Successful',
	                    array('params' => array('class'=>'alert-success'))
	                );
	            } else {
	            	$this->Flash->alertBox(
	                    'There Was An Error! Please Try Again',
	                    array('params' => array('class'=>'alert-danger'))
	                );
	            }
				$title = 'Departments';
				$index = 'Department';
				$data = $this->Department->pickListAll();
				break;
			
			default:
				break;
		}
		$this->set('records', $data);
    	$this->set('title', $title);
    	$this->set('index', $index);
    	$this->set('saveType', $value);
		return $this->redirect(array('controller'=>'Settings','action' => 'view', $value));
	}
}