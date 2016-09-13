<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class TrnCat extends AppModel {
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
    public $belongsTo = array( 
        'Training',
        'TrainingCategory',
    );
    
    public function getTrainingIds($id){
        $data = $this->find('list', array(
            'conditions' => array(
                'TrnCat.training_category_id' => $id,
            ),
            'contain'=>array(),
            'fields'=>array('TrnCat.training_id'),
            'order'=>array('TrnCat.training_id')
        ));
        return $data; 
    }

}
