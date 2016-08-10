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
            'foreignKey' => 'foreign_key_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Account' => array(
            'className' => 'Account',
            'foreignKey' => 'foreign_key_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public function getRequiredTraining($account_ids = null, $department_ids = null){
        
        $requiredTraining = $this->find('all', array(
            'conditions'=>array(
                'OR' => array(
                    array(
                        'AND' => array(
                            'model' => 'account',
                            'foreign_key_id' => $account_ids,
                        )
                    ),
                    array(
                        'AND' => array(
                            'model' => 'department',
                            'foreign_key_id' => $department_ids,
                        )
                    )
                )
            ),
            'contain'=>array(
                'Training'=>array(
                    'fields'=>array(
                        'Training.id',
                        'Training.name'
                    )
                )
            )
        ));
        
        return $requiredTraining;
    }
}
