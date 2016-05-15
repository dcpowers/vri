<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class AuthRole extends AppModel {
    public $actsAs = array('Containable', 'Multivalidatable');
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
    
    //The Associations below have been created with all possible keys, those that are not needed can be removed

    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $hasMany = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'auth_role_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
    );
    
    public function pickListByRole( $role_id=null ) {
        $dataArr = array();
        
        $parent = $this->find('first', array(
            'conditions' => array(
                $this->alias.'.id' => $role_id
            ),
            'contain'=>array(
            ),
            'fields'=>array($this->alias.'.lft', $this->alias.'.rght')
        ));
        
        $data = $this->find('threaded', array(
            'conditions' => array(
                $this->alias.'.lft >=' => $parent[$this->alias]['lft'], 
                $this->alias.'.rght <=' => $parent[$this->alias]['rght']
            ),
            'contain'=>array(
            ),
            'order'=>array($this->alias.'.lft')
        ));
        $dataArr = Set::extract( $data, '{n}.AuthRole.id' );
        
        return $dataArr;
    }
}