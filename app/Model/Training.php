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
}