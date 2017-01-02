<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class AccountDepartment extends AppModel {
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
        'Account',
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
        return $items;
    }

    public function pickListByAccount($id=null){
        $items = $this->find('all', array(
            'conditions' => array(
                $this->alias.'.account_id'=>$id
            ),
            'contain'=>array(
                'Department'=>array(
                    'fields'=>array(
                        'Department.id',
                        'Department.name'
                    )
                )
            ),
            'fields'=>array(),
        ));

        foreach ( $items as $key=>$rec ) {
            $dataArr[$rec['Department']['id']] = $rec['Department']['name'];
        }
        #pr($dataArr);
        #exit;
        return $dataArr;
    }

    public function getDepartmentIds($account_id=null){
        $dataArr = array();

        $items = $this->find('all', array(
            'conditions' => array(
                $this->alias.'.account_id'=>$account_id
            ),
            'contain'=>array(

            ),
            'fields'=>array($this->alias.'.department_id'),
        ));

        foreach ( $items as $key=>$rec ) {
            $dataArr[$rec['AccountDepartment']['department_id']] = $rec['AccountDepartment']['department_id'];
        }
        return $dataArr;
    }
}
