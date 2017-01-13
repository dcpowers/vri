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
}