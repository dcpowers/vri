<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class Award extends AppModel {
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
        'Account' => array(
            'className' => 'Account',
            'foreignKey' => 'account_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Dept' => array(
            'className' => 'Department',
            'foreignKey' => 'department_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => array('User.id', 'User.first_name', 'User.last_name',),
            'order' => ''
        ),
		'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'verified_by',
            'conditions' => '',
            'fields' => array('CreatedBy.id', 'CreatedBy.first_name', 'CreatedBy.last_name',),
            'order' => ''
        ),
		'Type' => array(
            'className' => 'AwardType',
            'foreignKey' => 'award_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
	);

    public $hasMany = array(
		'',
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