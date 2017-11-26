<?php
App::uses('AppController', 'Controller');
/**
 * Groups Controller
 *
 * @property Associate $Associate
 */
class AccidentStatementsController extends AppController {

    //public $components = array('Search.Prg');
    #public $helpers = array( 'Tree' );
    //Search Plugin

    var $uses = array(
        'AccidentStatement',
        'AccidentFile'
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

    public function index($id=null) {
    	$accident_info = $this->AccidentFile->find('first', array(
            'conditions' => array(
                'AccidentFile.created_by' => AuthComponent::user('id'),
                'AccidentFile.id' => $id,
            ),
            'contain'=>array(
            ),
        ));
        
        $this->request->data['AccidentFile']['id'] = $id;
    	
    	$parent = $this->AccidentStatement->find('first', array(
            'conditions' => array(
                'AccidentStatement.id' => $accident_info['AccidentFile']['statement_id']
            ),
            'contain'=>array(
            ),
            'fields'=>array('AccidentStatement.lft', 'AccidentStatement.rght')
        ));

        $result = $this->AccidentStatement->find('threaded', array(
            'conditions' => array(
                'AccidentStatement.lft >=' => $parent['AccidentStatement']['lft'],
                'AccidentStatement.rght <=' => $parent['AccidentStatement']['rght']
            ),
            'contain'=>array(
            ),
            //'fields'=>array('Group.id', 'Group.name', 'Group.supervisor_id')
        ));

		#pr($result);
		#exit;
		$this->set('accidents', $result);
		$this->set('options', $this->AccidentStatement->yesNo());

    }

	public function statements($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            #pr($this->request->data);
            #exit;
			$c=0;
			
			$this->request->data['AccidentFile']['id'] = $this->request->data['AccidentFile']['id'];
			$this->request->data['AccidentFile']['statement'] = serialize($this->request->data['Accidents']);
			$this->request->data['AccidentFile']['is_active'] = 2;
			
			unset(
				$this->request->data['Accidents']
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

			$this->redirect(array('controller'=>'Dashboard', 'action'=>'index'));
        }

        $this->set('costLov', $this->AccidentCostLov->pickList());
        $this->request->data['Accident']['id'] = $id;
    }
}