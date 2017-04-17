<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class TestCategoryType extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $displayField = 'id';
    
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array( 
        'Test',
    );
    
    /*public $hasMany = array(
        'JobStatus' => array(
            'className' => 'DetailUser',
            'foreignKey' => 'job_status'
        ),
        'Gender' => array(
            'className' => 'DetailUser',
            'foreignKey' => 'gender'
        )
    );*/
}
