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
    public $belongsTo = array( 
        'Training',
    );
    
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
}
