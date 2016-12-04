<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class TrainingClassroom extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
	public $displayField = 'id';
    
    public $actsAs = array('Containable', 'Multivalidatable');
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array( 
        'Training' => array(
            'className' => 'Training',
            'foreignKey' => 'training_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Instructor' => array(
            'className' => 'User',
            'foreignKey' => 'instructor_id',
            'conditions' => '',
            'fields' => array('Instructor.first_name', 'Instructor.last_name'),
            'order' => ''
        ),
        'Account' => array(
            'className' => 'Account',
            'foreignKey' => 'account_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public $hasMany = array(
        'TrainingClassroomDetail',
    );
}
