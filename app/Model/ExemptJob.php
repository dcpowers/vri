<?php
App::uses('AppModel', 'Model');

class ExemptJob extends AppModel {
     public $actsAs = array('Containable');
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array( 
        'User',
        'JobPosting'
    );
    
    public function pick_list($id=null){
        $data = $this->find('all', array(
            'conditions' => array(
                'ExemptJob.job_posting_id' => $id
            ),
            'contain'=>array(
            ),
            'fields'=>array('ExemptJob.user_id', 'ExemptJob.posted_by')
        ));
        
        foreach($data as $key=>$item){
            if($item['ExemptJob']['user_id'] != $item['ExemptJob']['posted_by']){
                unset($data[$key]);    
            }
        }
        
        $data = Set::extract( $data, '/ExemptJob/user_id' );
        return $data;
    }
}