<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class TrainingClassroomDetail extends AppModel {
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
        'Classroom' => array(
            'className' => 'TrainingClassroom',
            'foreignKey' => 'id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'User_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
}
