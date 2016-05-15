<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class Account extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
    public function beforeFilter() {
        parent::beforeFilter();
        
    }

    public function parentNode() {
        return null;
    }
    
    public $actsAs = array('Containable', 'Multivalidatable');
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(
        'Manager' => array(
            'className' => 'User',
            'foreignKey' => 'manager_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Coordinator' => array(
            'className' => 'User',
            'foreignKey' => 'coordinator_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'RegionalAdmin' => array(
            'className' => 'User',
            'foreignKey' => 'regional_admin_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Status' => array(
            'className' => 'Setting',
            'foreignKey' => 'is_active',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    public $hasMany = array(
        'AccountDepartment',
        'DepartmentUser',
        'User'
    );
    
    
    public $validationSets = array( 
        'name' => array(
            'rule' => 'notEmpty',
            'message' => 'This field cannot be left blank'
        )
    );
    
    public function pickListActive(){
        $data = $this->find('list', array(
            'conditions' => array(
                'Account.is_active' => 1
            ),
            'contain'=>array(),
            'fields'=>array('Account.id', 'Account.name'),
            'order'=>array('Account.name')
        ));
        return $data; 
    }
}