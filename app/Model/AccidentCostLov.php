<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class AccidentCostLov extends AppModel {
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
        'AccidentCost',
    );
}