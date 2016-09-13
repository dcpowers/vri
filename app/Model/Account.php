<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class Account extends AppModel {
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
        'Manager' => array(
            'className' => 'User',
            'foreignKey' => 'manager_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Coordinator' => array(
            'className' => 'User',
            'foreignKey' => 'coordinator_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'RegionalAdmin' => array(
            'className' => 'User',
            'foreignKey' => 'regional_admin_id',
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
    );
    
    public $hasMany = array(
        'TrainingMembership'=> array(
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'group' => array(
                'TrainingMembership.training_id',
                'TrainingMembership.account_id',
            )
        ),
        'AccountDepartment',
        'DepartmentUser',
        'User',
        'Asset'
    );
    
    
    public $validationSets = array( 
        'name' => array(
            'rule' => 'notEmpty',
            'message' => 'This field cannot be left blank'
        )
    );
    
    public function pickListActive(){
        $option = null;
        if(AuthComponent::user('Role.permission_level') == 50){
            $option = array('Account.regional_admin_id' => AuthComponent::user('id'));
        }
        
        if(AuthComponent::user('Role.permission_level') <= 40){
            $account_ids = Hash::extract(AuthComponent::user(), 'AccountUser.{n}.account_id');
            $option = array('Account.id' => $account_ids);
        }
        
        $data = $this->find('list', array(
            'conditions'=>array(
                'Account.is_active' => 1,
                $option
            ),
            'contain'=>array(),
            'fields'=>array('Account.id', 'Account.name'),
            'order'=>array('Account.name')
        ));
        #debug($this->validationErrors); //show validationErrors
        #debug($this->getDataSource()->getLog(false, false)); //show last sql query
        #pr($data);
        #exit;
        return $data; 
    }
    
    public function myAccounts(){
        $ids = $this->find('list', array(
            'conditions'=>array(
                'Account.regional_admin_id' => AuthComponent::user('id')
            ),
        ));
        
        return $ids;
    }
    
    public function pickListById( $ids=null ) {
        $dataArr = array();
        
        $find_options = array(
            'conditions'=>array(
                $this->alias.'.id'=>$ids
            ),
            'order'=>$this->alias.'.name asc'
        );

        #pr($find_options);
        #exit;
        $recs = $this->find('list', $find_options );
        
        #pr($recs);
        #exit;
        return $recs;
    }
}