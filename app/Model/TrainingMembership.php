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
    public $actsAs = array('Containable', 'Multivalidatable');
    
    public $belongsTo = array( 
        'Training',
        'Department' => array(
            'className' => 'Department',
            'foreignKey' => 'department_id',
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
        'RequiredUser' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'created_by',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public function getRequiredTraining($account_ids = null,$department_ids = null, $user_ids = null ){
        
        $training_ids = $this->find('all', array(
            'conditions'=>array(
                'OR' => array(
                    array(
                        'AND'=>array(
                            'TrainingMembership.account_id' => $account_ids,
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.department_id' => $department_ids,
                            'TrainingMembership.is_manditory' => 1
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.user_id' => $user_ids,
                            'TrainingMembership.is_manditory' => 1
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.account_id' => null,
                            'TrainingMembership.department_id' => null,
                            'TrainingMembership.user_id' => null,
                            'TrainingMembership.is_manditory' => 1
                        )
                    ),
                )
            )
        ));
        
        #pr($training_ids);
        #exit;
        return $training_ids;
    }
    
}
