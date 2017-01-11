<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class AccidentFile extends AppModel {
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
        'Accident',
		'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'created_by',
            'conditions' => '',
            'fields' => array('CreatedBy.id', 'CreatedBy.first_name', 'CreatedBy.last_name',),
            'order' => ''
        ),
    );

    public function pickList($id=null){
        $dataArr = array();

        $items = $this->find('list', array(
            'conditions' => array(
                $this->alias.'.accident_id'=>$id
            ),
        ));

        return $items;
	}
}
