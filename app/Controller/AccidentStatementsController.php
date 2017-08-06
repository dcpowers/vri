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

    public function index($id=null, $acc_id=null) {
		$parent = $this->AccidentStatement->find('first', array(
            'conditions' => array(
                'AccidentStatement.id' => $id
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
		$this->set('accident_id', $acc_id);
        $this->set('options', $this->AccidentStatement->yesNo());

    }

	public function statements($id=null){
        if ($this->request->is('post') || $this->request->is('put')) {
            pr($this->request->data);
            exit;
			$c=0;
			$id = $this->request->data['Accident']['accident_id'];
			foreach($this->request->data['AccidentFile'] as $v){
				if($v['files']['error'] == 0){
					$this->request->data[$c]['AccidentFile']['name'] = $this->upload($v['files']);
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
}