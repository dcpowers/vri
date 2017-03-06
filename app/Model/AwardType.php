<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class AwardType extends AppModel {
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
        'Award'=>array(
			'className' => 'Award',
            'foreignKey' => 'award_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
		),
	);


    public function pickList($id=null){
        $dataArr = array();

        $items = $this->find('list', array(
            'conditions' => array(
                $this->alias.'.is_active'=>1
            ),
        ));

        return $items;
	}
}
