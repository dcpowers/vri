<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class AccountUser extends AppModel {
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
        'Account' => array(
            'className' => 'Account',
            'foreignKey' => 'account_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    
    public function pickList($account_id=null){
        $dataArr = array();
        
        $items = $this->find('all', array(
            'conditions' => array(
                $this->alias.'.account_id'=>$account_id
            ),
            'contain'=>array(
                'User'=>array(
                    'fields'=>array(
                        'User.id',
                        'User.first_name',
                        'User.last_name',
                    ),
                    'order'=>array(
                        'User.first_name' => 'ASC',
                        'User.last_name' => 'ASC'
                    )
                )
            ),
            'fields'=>array(),
        ));
        
        foreach ( $items as $key=>$rec ) {
            $dataArr[$rec['User']['id']] = ucwords( strtolower($rec['User']['first_name'])) . ' ' . ucwords( strtolower($rec['User']['last_name'] ));
        }
        
        return $dataArr;
    }
    
    public function getAccountIds($account_id=null){
        $dataArr = array();
        
        $items = $this->find('all', array(
            'conditions' => array(
                $this->alias.'.account_id'=>$account_id
            ),
            'contain'=>array(
                'User'=>array(
                    'fields'=>array(
                        'User.id',
                    )
                )
            ),
            'fields'=>array(),
        ));
        foreach ( $items as $key=>$rec ) {
            $dataArr[$rec['User']['id']] = $rec['User']['id'];
        }
        return $dataArr;
    }
    
    public function pick_list_user(){
        $items = $this->find('list', array(
            'conditions' => array(),
            'contain'=>array(
            ),
            'fields'=>array(),
        ));
        return $items;
    }
}
