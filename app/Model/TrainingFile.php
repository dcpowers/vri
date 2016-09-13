<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class TrainingFile extends AppModel {
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
                'Trainingfile.is_active' => 1
            ),
            'contain'=>array(),
            'fields'=>array('TrainingFile.id', 'Trainingfile.file'),
            'order'=>array('TrainingFile.file')
        ));
        return $data; 
    }
}
