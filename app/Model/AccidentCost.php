<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class AccidentCost extends AppModel {
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
		'AccidentCostLov',
		'CreatedBy' => array(
            'className' => 'User',
            'foreignKey' => 'created_by',
            'conditions' => '',
            'fields' => array('CreatedBy.id', 'CreatedBy.first_name', 'CreatedBy.last_name',),
            'order' => ''
        ),
    );

    public $hasMany = array(

    );

	public function get_info($id=null){
		$info = $this->find('first', array(
			'conditions'=>array(
				'AccidentCost.id'=>$id
			)
		));

		return $info;

	}
}
