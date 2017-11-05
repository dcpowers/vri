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
			'contain'=>array()

        ));
		$list = array();

        foreach($data as $v){
			if(!empty($v['EmailList']['email'])){
				$list[$v['EmailList']['email']] = $v['EmailList']['name'];
			}
		}
		#pr($list);
		#exit;
		return $list;
    }
}