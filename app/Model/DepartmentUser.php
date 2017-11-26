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

    public function pickListByDept($id=null){
        $dataArr = array();

        $items = $this->find('all', array(
            'conditions' => array(
                $this->alias.'.department_id'=>$id,
            ),
            'contain'=>array(
                'User'=>array(
                    'fields'=>array(
                        'User.id',
                        'User.first_name',
                        'User.last_name',
                        'User.is_active',
                    ),
                    'order'=>array(
                        'User.first_name' => 'ASC',
                        'User.last_name' => 'ASC'
                    ),
                    'AccountUser'=>array(
                        'conditions'=>array(
                            'AccountUser.account_id'=>AuthComponent::user('AccountUser.0.account_id'),
                        )
                    )

                )
            ),
            'fields'=>array(),
        ));

        foreach ( $items as $key=>$rec ) {
            if(!empty($rec['User']['AccountUser']) && $rec['User']['is_active'] == 1){
                $dataArr[$rec['User']['id']] = ucwords( strtolower($rec['User']['first_name'])) . ' ' . ucwords( strtolower($rec['User']['last_name'] ));
            }
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
        return $users;
    }

    public function getUserIds($id=null){
        $dataArr = array();

        $items = $this->find('all', array(
            'conditions' => array(
                $this->alias.'.deparment_id'=>$id
            ),
            'contain'=>array(

            ),
            'fields'=>array($this->alias.'.department_id'),
        ));

        foreach ( $items as $key=>$rec ) {
            $dataArr[$rec['User']['id']] = $rec['User']['id'];
        }
        return $dataArr;
    }

	public function removeUserIdsByDept($users=null, $dept_ids = null){
        $dataArr = array();

        $items = $this->find('list', array(
            'conditions' => array(
                $this->alias.'.user_id'=>$users,
                $this->alias.'.department_id !='=>$dept_ids
            ),
            'contain'=>array(

            ),
            'fields'=>array($this->alias.'.user_id', $this->alias.'.user_id'),
        ));

		return $items;
    }
}
