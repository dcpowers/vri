<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class TrainingCategory extends AppModel {
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
    
    
    public function pickList(){
        $data = $this->find('list', array(
            'conditions' => array(
                'TrainingCategory.is_active' => 1
            ),
            'contain'=>array(),
            'fields'=>array('TrainingCategory.id', 'TrainingCategory.name'),
            'order'=>array('TrainingCategory.name')
        ));
        return $data; 
    }
    
    public function pickListAll(){
        $data = $this->find('all', array(
            'conditions' => array(
                #'TrainingCategory.is_active' => 1
            ),
            'contain'=>array(),
            #'fields'=>array('TrainingCategory.id', 'TrainingCategory.name'),
            'order'=>array('TrainingCategory.name')
        ));
        
        return $data; 
    }
    
    public function getRecord( $id=null ) {
        $dataArr = array();
		
		$find_options = array(
            'conditions'=>array(
                $this->alias.'.id'=>$id
            ),
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('first', $find_options );
		
		return $recs;
	
    }
}
