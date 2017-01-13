<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class AccidentStatement extends AppModel {
    /**
    * Display field
    *
    * @var string
    */
	public $displayField = 'id';

    public $actsAs = array('Containable', 'Tree');
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array(

    );

    public $hasMany = array(

    );
}
