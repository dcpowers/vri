<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class Training extends AppModel {
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
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'updated_by_id',
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
        'Account' => array(
            'className' => 'Account',
            'foreignKey' => 'account_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Department' => array(
            'className' => 'Department',
            'foreignKey' => 'department_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        
        
    );
    
    public $hasMany = array(
        'TrainingMembership',
        'TrainingUser',
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