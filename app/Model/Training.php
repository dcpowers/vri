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
        'Author' => array(
            'className' => 'User',
            'foreignKey' => 'author_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'UpdatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'author_id',
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
        'Public' => array(
            'className' => 'Setting',
            'foreignKey' => 'is_public',
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
    );
    
    public $hasMany = array(
        'TrainingMembership',
        'TrainingClassroom',
        'TrainingRecord',
        'TrnCat',
        'TrainingFile',
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
                'Training.is_active' => 1,
                'Training.is_public' => 1
            ),
            'contain'=>array(),
            'fields'=>array('Training.id', 'Training.name'),
            'order'=>array('Training.name')
        ));
        return $data; 
    }
    
    public function pickListById( $id = null){
        $data = $this->find('first', array(
            'conditions' => array(
                'Training.id' => $id
            ),
            'contain'=>array(),
            'fields'=>array('Training.id', 'Training.name'),
            'order'=>array('Training.name')
        ));
        
        return $data['Training']['name']; 
    }
    
    public function getTraining($trnIds = null, $userId = null){
    	$acctIds = Hash::extract(AuthComponent::user(), 'AccountUser.{n}.account_id');
    	$deptIds = Hash::extract(AuthComponent::user(), 'DepartmentUser.{n}.department_id');
    	
    	#pr($trnIds);
    	#exit;
		$c = 0;
		foreach($trnIds as $trn){
			$data[$c] = $this->find('first', array(
	            'conditions' => array(
	                'Training.id' => $trn['TrainingMembership']['training_id']
	            ),
	            'contain'=>array(
	            	'TrainingRecord'=>array(
	            		'conditions'=>array(
	            			'TrainingRecord.user_id' => $userId
	            		),
	            		'order'=>array('TrainingRecord.created' => 'DESC'),
	            		'limit'=>1
	            	),
	            	'TrainingFile'=>array()
	            	
	            ),
	            #'fields'=>array('Training.id', 'Training.name'),
	            'order'=>array('Training.name')
	        ));
	        
	        $data[$c]['TrainingMembership']	= $trn['TrainingMembership'];
	        
	        $c++;
		}
        
        #pr($data);
        #exit;
        return $data;
	}
}