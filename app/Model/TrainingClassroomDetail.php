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
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => array('User.first_name', 'User.last_name'),
            'order' => ''
        ),
    );
}
