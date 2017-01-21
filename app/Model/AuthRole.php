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
            'fields'=>array($this->alias.'.permission_level')
        ));

        $data = $this->find('all', array(
            'conditions' => array(
                $this->alias.'.permission_level <=' => $parent[$this->alias]['permission_level'],
            ),
            'contain'=>array(
            ),
            'order'=>array($this->alias.'.lft')
        ));

        foreach($data as $rec=>$v){
            $dataArr[$v['AuthRole']['id']] = $v['AuthRole']['name'];
        }

        return $dataArr;
    }
}