<?php
App::uses('AppModel', 'Model');
/**
 * Group Model
 *
 */
class EmailList extends AppModel {
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
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'User_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
        'Application' => array(
            'className' => 'Application',
            'foreignKey' => 'application_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );


    public function pickList($app_id = null){

		$data = $this->find('all', array(
            'conditions'=>array(
                'EmailList.application_id' => $app_id
            ),
            'contain'=>array(
				'User'=>array(
					'fields'=>array(
						'User.email',
						'User.first_name',
						'User.last_name',
					)
				)
			),
        ));

		foreach($data as $v){
			$list[$v['User']['email']] = $v['User']['first_name'].' '.$v['User']['last_name'];
		}
		#pr($list);
		#exit;
		return $list;
    }
}