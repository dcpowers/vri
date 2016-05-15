<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class Setting extends AppModel {
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
    
    public $actsAs = array('Containable');
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $hasMany = array(
        'Account' => array(
            'className' => 'Account',
            'foreignKey' => 'is_active',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'is_active',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    public function pickList($type=null) {
        $dataArr = array();
        
        $find_options = array(
            'conditions'=>array(
                $this->alias.'.type'=>$type
            ),
            'order'=>$this->alias.'.name asc',
            'fields'=>array($this->alias.'.id', $this->alias.'.name')
        );

        //pr($find_options);
        //exit;
        $recs = $this->find('list', $find_options );

        return $recs;
    }
}