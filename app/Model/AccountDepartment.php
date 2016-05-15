<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class AccountDepartment extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
	public $displayField = 'id';
    
    public $actsAs = array('Containable');
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array( 
        'Account',
        'Department' => array(
            'className' => 'Department',
            'foreignKey' => 'department_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public function pick_list_dept(){
        $items = $this->find('list', array(
            'conditions' => array(),
            'contain'=>array(
            ),
            'fields'=>array(),
        ));
        return $users;
    }
    
    public function pick_list_acct(){
        $items = $this->find('list', array(
            'conditions' => array(),
            'contain'=>array(
            ),
            'fields'=>array(),
        ));
        return $users;
    }
}
