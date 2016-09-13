<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class DepartmentUser extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
	public $displayField = 'id';
    
    public $actsAs = array('Containable');
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array( 
        'User',
        'Department' => array(
            'className' => 'Department',
            'foreignKey' => 'department_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public function pick_list_dept(){
        $items = $this->find('list', array(
            'conditions' => array(),
            'contain'=>array(
            ),
            'fields'=>array(),
        ));
        return $users;
    }
    
    public function pick_list_user(){
        $items = $this->find('list', array(
            'conditions' => array(),
            'contain'=>array(
            ),
            'fields'=>array(),
        ));
        return $users;
    }
    
    public function getUserIds($account_id=null){
        $dataArr = array();
        
        $items = $this->find('all', array(
            'conditions' => array(
                $this->alias.'.account_id'=>$account_id
            ),
            'contain'=>array(
                
            ),
            'fields'=>array($this->alias.'.department_id'),
        ));
        
        pr($items);
        exit;
        foreach ( $items as $key=>$rec ) {
            $dataArr[$rec['User']['id']] = $rec['User']['id'];
        }
        return $dataArr;
    }
}
