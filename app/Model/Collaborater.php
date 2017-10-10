<?php
App::uses('AppModel', 'Model');

/**
 * GroupMembership Model
 *
 */
class Collaborater extends AppModel {
/**
 * Display field
 *
 * @var string
 */
	public $actsAs = array('Containable');
    /**
     * belongsTo associations
     *
     * @var array
     */
    public $belongsTo = array( 
        'JobPosting'=> array(
          'className' => 'JobPosting',
          'foreignKey' => 'job_posting_id',
          'dependent' => true
        ),
        'User'
    );
}
