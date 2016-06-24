<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class TrainingMembership extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
	public $displayField = 'id';
    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array( 
        'Training',
        'Department' => array(
            'className' => 'Department',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Account' => array(
            'className' => 'Account',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
