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
            'foreignKey' => 'traininer_id',
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
        )
    );
    
    public function findExpired(){
        
    }
    
    public function findRecords($requiredTraining = null, $user_id = null){
        $data = array();
        foreach($requiredTraining as $training){
            #pr($training);
            #exit;
            $records = $this->find('all', array(
                'conditions'=> array(
                    $this->alias.'.training_id'=>$training['TrainingMembership']['training_id'],
                    $this->alias.'.user_id'=>$user_id
                ),
                'contain'=>array(
                ),
                'order'=>array($this->alias.'.completed_on' => 'DESC')
            ));
            
            if(empty($records)){
                $data[$training['TrainingMembership']['training_id']][0]['TrainingRecord']['in_progress'] = 0;
                $data[$training['TrainingMembership']['training_id']][0]['TrainingRecord']['expired'] = 1;
                $data[$training['TrainingMembership']['training_id']][0]['TrainingRecord']['expiring'] = 0;
                $data[$training['TrainingMembership']['training_id']][0]['TrainingRecord']['no_record'] = 1;
            }
            
            foreach($records as $record){
                $record['TrainingRecord']['no_record'] = 0;
                
                if(!is_null($record['TrainingRecord']['started_on']) && is_null($record['TrainingRecord']['completed_on'])){
                    $record['TrainingRecord']['in_progress'] = 1;
                }else{
                    $record['TrainingRecord']['in_progress'] = 0;
                }
                
                if(strtotime($record['TrainingRecord']['expires_on']) < strtotime('now')){
                    $record['TrainingRecord']['expired'] = 1;
                }else{
                    $record['TrainingRecord']['expired'] = 0;
                }
                
                if(strtotime($record['TrainingRecord']['expires_on']) >= strtotime('now') && strtotime($record['TrainingRecord']['expires_on']) <= strtotime('+30 days') ){
                    $record['TrainingRecord']['expiring'] = 1;
                }else{
                    $record['TrainingRecord']['expiring'] = 0;
                }
                
                $data[$record['TrainingRecord']['training_id']][] = $record;
                
            }
            
            #'expires_on <' =>  date(DATE_MYSQL_DATETIME, strtotime( '+' . $days . ' days', time() ) )
        }
        #pr($data);
        #exit;
        return $data;
    }
}
