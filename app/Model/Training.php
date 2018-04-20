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
    	#pr($trnIds);
    	#exit;
		$c = 0;
		foreach($trnIds as $trn){
			$record = $this->find('first', array(
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
	            	'TrainingFile'=>array(),
	            	'TrainingMembership'=>array()
	            	
	            ),
	            #'fields'=>array('Training.id', 'Training.name'),
	            'order'=>array('Training.name')
	        ));
	        
	        $data[$c]['TrainingRecord'] = $record['Training'];
	        $data[$c]['TrainingRecord']['no_record'] = 0;
	        $data[$c]['TrainingRecord']['expired'] = 0;
	        $data[$c]['TrainingRecord']['expiring'] = 0;
	        $data[$c]['TrainingRecord']['in_progress'] = 0;
	        $data[$c]['TrainingFile'] = $record['TrainingFile'];
	        
	        if(empty($record['TrainingRecord'])){
	        	$data[$c]['TrainingRecord']['no_record'] = 1;
            	if($record['TrainingMembership'][0]['is_required'] == 1 || $record['TrainingMembership'][0]['is_manditory'] == 1){
					$data[$c]['TrainingRecord']['is_required'] = 1;
				}else{
					$data[$c]['TrainingRecord']['is_required'] = 0;
				}
            } else {
            	$data[$c]['TrainingRecord'] += $record['TrainingRecord'][0];
				$data[$c]['TrainingRecord']['no_record'] = 0;
                
                if($record['TrainingMembership'][0]['is_required'] == 1 || $record['TrainingMembership'][0]['is_manditory'] == 1){
					$data[$c]['TrainingRecord']['is_required'] = 1;
				}else{
					$data[$c]['TrainingRecord']['is_required'] = 0;
				}
                
                if(empty($record['TrainingRecord'][0]['started_on'])){
                    $data[$c]['TrainingRecord']['in_progress'] = 0;
                }else if(!is_null($record['TrainingRecord'][0]['started_on']) && is_null($record['TrainingRecord'][0]['completed_on'])){
                    $data[$c]['TrainingRecord']['in_progress'] = 1;
                }else{
                    $data[$c]['TrainingRecord']['in_progress'] = 0;
                }

                if(strtotime($record['TrainingRecord'][0]['expires_on']) < strtotime('now')){
                    $data[$c]['TrainingRecord']['expired'] = 1;
                }else{
                    $data[$c]['TrainingRecord']['expired'] = 0;
                }

                if(strtotime($record['TrainingRecord'][0]['expires_on']) >= strtotime('now') && strtotime($record['TrainingRecord'][0]['expires_on']) <= strtotime('+30 days') ){
                    $data[$c]['TrainingRecord']['expiring'] = 1;
                }else{
                    $data[$c]['TrainingRecord']['expiring'] = 0;
                }
                
                #pr($data);
                #exit;
			}
            #$data[$c]['TrainingMembership']	= $trn['TrainingMembership'];
	        
	        $c++;
		}
        
        #pr($data);
        #exit;
        return $data;
	}
}