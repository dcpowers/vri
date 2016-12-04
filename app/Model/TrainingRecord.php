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
        $excludeIds = array();
        foreach($requiredTraining as $training){
            $trnId = $training['Training']['id'];
            
            #pr($training);
            #exit;
            $records = $this->find('first', array(
                'conditions'=> array(
                    $this->alias.'.training_id'=>$training['TrainingMembership']['training_id'],
                    $this->alias.'.user_id'=>$user_id
                ),
                'contain'=>array(
                ),
                'order'=>array(
                    $this->alias.'.completed_on' => 'DESC',
                    $this->alias.'.expires_on' => 'DESC',
                )
            ));
            #pr($records);
            #exit;
            if(empty($records)){
                $data[$trnId]['TrainingRecord'] = $training['Training'];
                $data[$trnId]['TrainingRecord']['in_progress'] = 0;
                $data[$trnId]['TrainingRecord']['expired'] = 1;
                $data[$trnId]['TrainingRecord']['expiring'] = 0;
                $data[$trnId]['TrainingRecord']['no_record'] = 1;
                $data[$trnId]['TrainingRecord']['is_required'] = 1;
                
            }else{
                $excludeIds[$training['Training']['id']] = $training['Training']['id'];
                #pr($training);
                #pr($records);
                #exit;
                $data[$trnId]['TrainingRecord'] = $training['Training'];
                $data[$trnId]['TrainingRecord'] += $records['TrainingRecord'];
                
                $data[$trnId]['TrainingRecord']['no_record'] = 0;
                $data[$trnId]['TrainingRecord']['is_required'] = 1;
                
                if(empty($records['TrainingRecord']['started_on'])){
                    $data[$trnId]['TrainingRecord']['in_progress'] = 0;
                }else if(!is_null($records['TrainingRecord']['started_on']) && is_null($records['TrainingRecord']['completed_on'])){
                    $data[$trnId]['TrainingRecord']['in_progress'] = 1;
                }else{
                    $data[$trnId]['TrainingRecord']['in_progress'] = 0;
                }
                
                if(empty($records['TrainingRecord']['expires_on'])){
                    $data[$trnId]['TrainingRecord']['expiring'] = 0;
                    $data[$trnId]['TrainingRecord']['expired'] = 0;
                }else{
                    if(strtotime($records['TrainingRecord']['expires_on']) < strtotime('now')){
                        $data[$trnId]['TrainingRecord']['expired'] = 1;
                    }else{
                        $data[$trnId]['TrainingRecord']['expired'] = 0;
                    }
                
                    if(strtotime($records['TrainingRecord']['expires_on']) >= strtotime('now') && strtotime($records['TrainingRecord']['expires_on']) <= strtotime('+30 days') ){
                        $data[$trnId]['TrainingRecord']['expiring'] = 1;
                    }else{
                        $data[$trnId]['TrainingRecord']['expiring'] = 0;
                    }
                }
                
                
            }
            #'expires_on <' =>  date(DATE_MYSQL_DATETIME, strtotime( '+' . $days . ' days', time() ) )
        }
        
        $allrecords = $this->find('all', array(
            'conditions'=> array(
                $this->alias.'.training_id !='=>$excludeIds,
                $this->alias.'.user_id'=>$user_id
            ),
            'contain'=>array(
                'Training'=>array(
                    'fields'=>array(
                        'Training.id',
                        'Training.name',
                        'Training.description',
                    )
                ),
            ),
            'order'=>array(
                $this->alias.'.completed_on' => 'DESC',
                $this->alias.'.expires_on' => 'DESC',
            )
        ));
        
        foreach($allrecords as $t){
            $trnId = $t['TrainingRecord']['training_id'];
            
            $t['TrainingRecord']['name'] = $t['Training']['name'];
            $t['TrainingRecord']['description'] = $t['Training']['description'];
            unset($t['Training']);
            
            $data[$trnId] = $t;
            $data[$trnId]['TrainingRecord']['no_record'] = 0;
            $data[$trnId]['TrainingRecord']['is_required'] = 0;
            
            if(empty($t['TrainingRecord']['started_on'])){
                $data[$trnId]['TrainingRecord']['in_progress'] = 0;
            }else if(!is_null($t['TrainingRecord']['started_on']) && is_null($t['TrainingRecord']['completed_on'])){
                $data[$trnId]['TrainingRecord']['in_progress'] = 1;
            }else{
                $data[$trnId]['TrainingRecord']['in_progress'] = 0;
            }
                
            if(empty($t['TrainingRecord']['expires_on'])){
                $data[$trnId]['TrainingRecord']['expiring'] = 0;
                $data[$trnId]['TrainingRecord']['expired'] = 0;
            }else{
                if(strtotime($t['TrainingRecord']['expires_on']) < strtotime('now')){
                    $data[$trnId]['TrainingRecord']['expired'] = 1;
                }else{
                    $data[$trnId]['TrainingRecord']['expired'] = 0;
                }
                
                if(strtotime($t['TrainingRecord']['expires_on']) >= strtotime('now') && strtotime($t['TrainingRecord']['expires_on']) <= strtotime('+30 days') ){
                    $data[$trnId]['TrainingRecord']['expiring'] = 1;
                }else{
                    $data[$trnId]['TrainingRecord']['expiring'] = 0;
                }
            }
        }
        #pr($allrecords);
        #pr($data);
        #exit;
        return $data;
    }
}
