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
        'ReqDept' => array(
            'className' => 'Department',
            'foreignKey' => 'department_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'ReqAcct' => array(
            'className' => 'Account',
            'foreignKey' => 'account_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'ReqUser' => array(
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

    public function getAllTraining($account_ids = null, $department_ids = null, $userId = null ){


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
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.user_id' => $userId,
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
            ),
			'contain'=>array(
				'Training'=>array(
					'TrainingFile'=>array(
						'conditions'=>array(
							'TrainingFile.file_type'=>'mp4'
						)
					)
				)
			)

		));

        #pr($training_ids);
        #exit;
        return $training_ids;
    }

	public function getRequiredTrainingIds($account_ids = null, $department_ids = null, $userId = null ){


        $trnIds = $this->find('all', array(
            'conditions'=>array(
                'OR' => array(
                    array(
                        'AND'=>array(
                            'TrainingMembership.account_id' => $account_ids,
							'OR'=>array(
								array('TrainingMembership.is_manditory' => 1,),
								array('TrainingMembership.is_required' => 1)
							)
                        )
                    ),
                   
                    array(
                        'AND'=>array(
                            'TrainingMembership.department_id' => $department_ids,
                            'OR'=>array(
								array('TrainingMembership.is_manditory' => 1,),
								array('TrainingMembership.is_required' => 1)
							)
                        )
                    ),
                    array(
                        'AND'=>array(
                            'TrainingMembership.user_id' => $userId,
                            'OR'=>array(
								array('TrainingMembership.is_manditory' => 1,),
								array('TrainingMembership.is_required' => 1)
							)
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
            ),
			'contain'=>array(
				
			),
			

		));
		
		#pr($trnIds);
        #exit;
        return $trnIds;
    }

}
