<?php
App::uses('AppController', 'Controller');
/**
 * GroupMemberships Controller
 *
 */
class AccountDepartmentsController extends AppController {
    public function beforeFilter() {
        parent::beforeFilter();

        $this->Auth->allow(
        );
    }

    public function pickListByDept($id=null){
        $items = $this->AccountDepartment->find('all', array(
            'conditions' => array(
                'AccountDepartment.account_id'=>AuthComponent::user('AccountUser.0.account_id'),
                'AccountDepartment.department_id'=>$id
            ),
            'contain'=>array(
                'Department'=>array(
                    'User'=>array(
                        'fields'=>array(
                            'User.id',
                            'User.first_name',
                            'User.last_name',
                        ),
                        'order'=>array(
                            'User.first_name' => 'ASC',
                            'User.last_name' => 'ASC'
                        ),
                    )
                ),
                'Account'=>array(
                    'AccountUser'=>array(
                        'User'=>array()
                    )
                )
            ),
            'fields'=>array(),
        ));

        pr($items);
        exit;
        foreach ( $items as $key=>$rec ) {
            $dataArr[$rec['User']['id']] = ucwords( strtolower($rec['User']['first_name'])) . ' ' . ucwords( strtolower($rec['User']['last_name'] ));
        }

        return $dataArr;
    }


}
