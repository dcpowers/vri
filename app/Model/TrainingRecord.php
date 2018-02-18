<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class TrainingRecord extends AppModel {
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
        'Training',
        'Trainer' => array(
            'className' => 'User',
            'foreignKey' => 'trainer_id',
            'conditions' => '',
            'fields' => array('Trainer.first_name', 'Trainer.last_name'),
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
		'Classroom' => array(
            'className' => 'TrainingClassroom',
            'foreignKey' => 'training_classroom_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );

    public function findExpired(){

    }

    public function findRecords($user_id = null){
    	$records = array();
        #pr($requiredTraining);
        #exit;
        $records = $this->find('all', array(
            'conditions'=> array(
                $this->alias.'.user_id'=>$user_id
            ),
            'contain'=>array(
            	'Training'=>array()
            ),
            'order'=>array(
                $this->alias.'.completed_on' => 'DESC',
                $this->alias.'.expires_on' => 'DESC',
            )
        ));
        #pr($records);
        #exit;
        return $records;
    }
}
